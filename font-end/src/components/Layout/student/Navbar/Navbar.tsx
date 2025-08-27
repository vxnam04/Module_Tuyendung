"use client";

import { useState, useRef, useEffect } from "react";
import styles from "./Navbar.module.css";
import {
  ChevronDown,
  Sun,
  Moon,
  Bell,
  MessageCircle,
  LifeBuoy,
  LogOut,
} from "lucide-react";
import Image from "next/image";

interface JobCategory {
  id: string;
  name: string;
}

export default function Navbar() {
  const [theme, setTheme] = useState<"light" | "dark">("light");
  const [mounted, setMounted] = useState(false);
  const [openDropdown, setOpenDropdown] = useState(false);
  const [jobCategories, setJobCategories] = useState<JobCategory[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const dropdownRef = useRef<HTMLDivElement>(null);

  // Mount check
  useEffect(() => {
    setMounted(true);
  }, []);

  // Apply theme to body
  useEffect(() => {
    document.body.setAttribute("data-theme", theme);
  }, [theme]);

  // Close dropdown when click outside
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (
        dropdownRef.current &&
        !dropdownRef.current.contains(event.target as Node)
      ) {
        setOpenDropdown(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  // Fetch industries from backend Laravel
  useEffect(() => {
    async function fetchIndustries() {
      try {
        // URL backend, dùng biến môi trường NEXT_PUBLIC_API_URL
        const baseUrl =
          process.env.NEXT_PUBLIC_API_URL || "http://localhost:8020";

        const res = await fetch(`${baseUrl}/api/job-industries`);
        if (!res.ok) throw new Error("Failed to fetch industries");

        const data: { industry_name: string }[] = await res.json();
        const categories = data.map((item) => ({
          id: item.industry_name,
          name: item.industry_name,
        }));
        setJobCategories(categories);
      } catch (err) {
        console.error(err);
        setError("Không thể tải danh mục ngành nghề");
      } finally {
        setLoading(false);
      }
    }
    fetchIndustries();
  }, []);

  return (
    <header className={styles.header}>
      <nav className={styles.navbar}>
        {/* Logo */}
        <div className={styles.logo}>
          TopCV
          <ul className={styles.menu}>
            <li className={styles.dropdown}>
              <a href="#">
                Việc làm <ChevronDown size={14} />
              </a>

              {/* Mega Menu */}
              <div className={styles.megaMenu}>
                <div className={styles.megaColumn}>
                  <h3>VIỆC LÀM</h3>
                  <ul>
                    <li>
                      <a href="#">Tìm việc làm</a>
                    </li>
                    <li>
                      <a href="#">Việc làm đã lưu</a>
                    </li>
                    <li>
                      <a href="#">Việc làm đã ứng tuyển</a>
                    </li>
                    <li>
                      <a href="#">Việc làm phù hợp</a>
                    </li>
                  </ul>
                </div>

                <div className={styles.megaColumn}>
                  <h3>VIỆC LÀM THEO NGÀNH NGHỀ</h3>
                  <ul className={styles.gridMenu}>
                    {loading ? (
                      <li>Loading...</li>
                    ) : error ? (
                      <li>{error}</li>
                    ) : (
                      jobCategories.map((cat) => (
                        <li key={cat.id}>
                          <a
                            href={`/jobs?category=${encodeURIComponent(
                              cat.id
                            )}`}
                          >
                            {cat.name}
                          </a>
                        </li>
                      ))
                    )}
                  </ul>
                </div>
              </div>
            </li>
          </ul>
        </div>

        {/* Actions */}
        <div className={styles.actions}>
          {/* Theme toggle */}
          {mounted &&
            (theme === "dark" ? (
              <Sun
                size={18}
                className={styles.icon}
                onClick={() => setTheme("light")}
              />
            ) : (
              <Moon
                size={18}
                className={styles.icon}
                onClick={() => setTheme("dark")}
              />
            ))}

          {/* Notification + Chat */}
          <Bell size={18} className={styles.icon} />
          <MessageCircle size={18} className={styles.icon} />

          {/* Avatar + Dropdown */}
          <div className={styles.avatarWrapper} ref={dropdownRef}>
            <Image
              src="https://i.pravatar.cc/40"
              alt="avatar"
              width={32}
              height={32}
              className={styles.avatar}
              onClick={() => setOpenDropdown(!openDropdown)}
            />
            {openDropdown && (
              <div className={styles.dropdownMenu}>
                <a href="#" className={styles.dropdownItem}>
                  <LifeBuoy size={16} /> Support
                </a>
                <a href="#" className={styles.dropdownItem}>
                  <LogOut size={16} /> Đăng xuất
                </a>
              </div>
            )}
          </div>

          {/* Button */}
          <a href="/auth/login" className={styles.btnPrimary}>
            <p className={styles.chumo}>Bạn là nhà tuyển dụng</p>
            <p className={styles.highlight}>Đăng tuyển ngay</p>
          </a>
        </div>
      </nav>
    </header>
  );
}
