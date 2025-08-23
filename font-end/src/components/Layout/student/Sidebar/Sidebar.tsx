"use client";

import {
  LayoutDashboard,
  FileText,
  Bell,
  Users,
  Settings,
  HelpCircle,
  LogOut,
  Newspaper,
} from "lucide-react";
import styles from "./Sidebar.module.css";

interface SidebarProps {
  collapsed: boolean;
}

export default function Sidebar({ collapsed }: SidebarProps) {
  return (
    <aside className={`${styles.sidebar} ${collapsed ? styles.collapsed : ""}`}>
      {/* User section */}
      <div className={styles.userSection}>
        <div className={styles.avatar}>U</div>
        <span className={styles.username}>Username</span>
      </div>

      <div className={styles.menuWrapper}>
        <nav className={styles.menu}>
          {/* Tổng quan */}
          <div className={styles.menuGroup}>
            <div className={styles.groupTitle}>Tổng quan</div>
            <a className={styles.menuItem}>
              <LayoutDashboard size={18} className={styles.icon} />
              <span>Bảng tin</span>
            </a>
            <a className={styles.menuItem}>
              <Users size={18} className={styles.icon} />
              <span>Tin tuyển dụng</span>
            </a>
            <a className={styles.menuItem}>
              <Newspaper size={18} className={styles.icon} />
              <span>Toppy AI - Đề xuất</span>
            </a>
          </div>

          {/* Quản lý tuyển dụng */}
          <div className={styles.menuGroup}>
            <div className={styles.groupTitle}>Quản lý tuyển dụng</div>
            <a className={styles.menuItem}>
              <FileText size={18} className={styles.icon} />
              <span>CV đề xuất</span>
              {/* Mai sau tài khoản sinh viên đăng cv lên sẽ hiện ở đây phân loại theo khoa */}
            </a>
            <a className={styles.menuItem}>
              <Users size={18} className={styles.icon} />
              <span>Quản lý CV</span>
            </a>
            <a className={styles.menuItem}>
              <Users size={18} className={styles.icon} />
              <span>Quản lý nhãn CV</span>
              {/* chia theo khoa, ngành */}
            </a>
            <a className={styles.menuItem}>
              <Users size={18} className={styles.icon} />
              <span>Quản lý yêu cầu kết nối CV</span>
              {/* trạng thái cv có phù hợp với doanh nghiệp hay không */}
            </a>
          </div>

          {/* Cài đặt & Hệ thống */}
          <div className={styles.menuGroup}>
            <div className={styles.groupTitle}>Cài đặt & Hệ thống</div>
            <a className={styles.menuItem}>
              <Settings size={18} className={styles.icon} />
              <span>Cài đặt tài khoản</span>
            </a>
            <a className={styles.menuItem}>
              <Bell size={18} className={styles.icon} />
              <span>Thông báo hệ thống</span>
            </a>
          </div>
        </nav>
      </div>

      {/* Bottom menu cố định đáy */}
      <div className={styles.bottomMenu}>
        <a className={styles.menuItem}>
          <HelpCircle size={18} className={styles.icon} />
          <span>Support</span>
        </a>
        <a className={`${styles.menuItem} ${styles.logout}`}>
          <LogOut size={18} className={styles.icon} />
          <span>Logout</span>
        </a>
      </div>
    </aside>
  );
}
