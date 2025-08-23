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
export default function Navbar() {
  const [theme, setTheme] = useState<"light" | "dark">("light");
  const [mounted, setMounted] = useState(false);
  const [openDropdown, setOpenDropdown] = useState(false);
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

  return (
    <header className={styles.header}>
      <nav className={styles.navbar}>
        {/* Logo */}
        <div className={styles.logo}>
          TopCV
          {/* Menu */}
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
                  <h3>VIỆC LÀM THEO VỊ TRÍ</h3>
                  <ul className={styles.gridMenu}>
                    <li>
                      <a href="#">Nhân viên kinh doanh</a>
                    </li>
                    <li>
                      <a href="#">Kế toán</a>
                    </li>
                    <li>
                      <a href="#">Marketing</a>
                    </li>
                    <li>
                      <a href="#">Hành chính nhân sự</a>
                    </li>
                    <li>
                      <a href="#">Chăm sóc khách hàng</a>
                    </li>
                    <li>
                      <a href="#">Ngân hàng</a>
                    </li>
                    <li>
                      <a href="#">IT</a>
                    </li>
                    <li>
                      <a href="#">Lao động phổ thông</a>
                    </li>
                    <li>
                      <a href="#">Senior</a>
                    </li>
                    <li>
                      <a href="#">Kỹ sư xây dựng</a>
                    </li>
                    <li>
                      <a href="#">Thiết kế đồ họa</a>
                    </li>
                    <li>
                      <a href="#">Bất động sản</a>
                    </li>
                    <li>
                      <a href="#">Giáo dục</a>
                    </li>
                    <li>
                      <a href="#">Telesales</a>
                    </li>
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
              width={32} // bắt buộc
              height={32} // bắt buộc
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
