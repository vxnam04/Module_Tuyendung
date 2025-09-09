"use client";

import {
  Bell,
  Sun,
  Moon,
  Menu,
  LogOut,
  LifeBuoy,
  FileText,
  Users,
  Link as LinkIcon,
} from "lucide-react";
import { useTheme } from "next-themes";
import { useEffect, useState, useRef } from "react";
import styles from "./Navbar.module.css";
import Image from "next/image";
import Link from "next/link"; // 👈 import Link từ next/link

interface NavbarProps {
  collapsed: boolean;
  setCollapsed: (val: boolean) => void;
}

export default function Navbar({ collapsed, setCollapsed }: NavbarProps) {
  const { theme, setTheme } = useTheme();
  const [mounted, setMounted] = useState(false);
  const [openDropdown, setOpenDropdown] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  useEffect(() => setMounted(true), []);

  useEffect(() => {
    function handleClickOutside(e: MouseEvent) {
      if (
        dropdownRef.current &&
        !dropdownRef.current.contains(e.target as Node)
      ) {
        setOpenDropdown(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  return (
    <div className={styles.header}>
      <div className={styles.topbarleft}>
        {/* Toggle sidebar */}
        <Menu
          size={20}
          className={styles.icon}
          onClick={() => setCollapsed(!collapsed)}
        />
      </div>

      {/* Actions */}
      <div className={styles.actions}>
        {/* Menu */}
        <nav className={styles.nav}>
          <Link href="/authorized/teacher/post" className={styles.navItem}>
            <FileText size={16} className={styles.navIcon} />
            Đăng tin
          </Link>
          <a href="#" className={styles.navItem}>
            <Users size={16} className={styles.navIcon} />
            Tìm CV
          </a>
          <a href="#" className={styles.navItem}>
            <LinkIcon size={16} className={styles.navIcon} />
            Connect
          </a>
        </nav>

        {/* Theme toggle */}
        {mounted && (
          <>
            {theme === "dark" ? (
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
            )}
          </>
        )}

        {/* Notification */}
        <Bell size={18} className={styles.icon} />

        {/* Avatar + Dropdown */}
        <div className={styles.avatarWrapper} ref={dropdownRef}>
          <Image
            src="https://i.pravatar.cc/40"
            alt="avatar"
            className={styles.avatar}
            onClick={() => setOpenDropdown(!openDropdown)}
            width={32}
            height={32}
          />
          {openDropdown && (
            <div className={styles.dropdown}>
              <a href="#" className={styles.dropdownItem}>
                <LifeBuoy size={16} className={styles.dropdownIcon} />
                Support
              </a>
              <a href="#" className={styles.dropdownItem}>
                <LogOut size={16} className={styles.dropdownIcon} />
                Đăng xuất
              </a>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
