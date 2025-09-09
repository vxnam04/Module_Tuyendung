"use client";

import {
  LayoutDashboard,
  FileText,
  Calendar,
  Bell,
  CheckSquare,
  Users,
  BookOpen,
  MessageSquare,
  Shield,
  Settings,
  HelpCircle,
  LogOut,
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

      {/* Menu wrapper scrollable */}
      <div className={styles.menuWrapper}>
        <nav className={styles.menu}>
          <p className={styles.menuGroup}>DASHBOARD</p>
          <a className={styles.menuItem}>
            <LayoutDashboard size={18} className={styles.icon} />
            <span>Analytics</span>
          </a>
          <a
            href="http://localhost:3000/authorized"
            className={styles.menuItem}
          >
            <FileText size={18} className={styles.icon} />
            <span>Documents</span>
          </a>
          <a className={styles.menuItem}>
            <Calendar size={18} className={styles.icon} />
            <span>Calendar</span>
          </a>
          <a className={styles.menuItem}>
            <Bell size={18} className={styles.icon} />
            <span>Notifications</span>
          </a>
          <a className={styles.menuItem}>
            <CheckSquare size={18} className={styles.icon} />
            <span>Tasks</span>
          </a>

          <p className={styles.menuGroup}>RELATIONSHIPS</p>
          <a className={styles.menuItem}>
            <Users size={18} className={styles.icon} />
            <span>Departments</span>
          </a>
          <a className={styles.menuItem}>
            <BookOpen size={18} className={styles.icon} />
            <span>Blog</span>
          </a>
          <a className={styles.menuItem}>
            <MessageSquare size={18} className={styles.icon} />
            <span>Chats</span>
          </a>

          <p className={styles.menuGroup}>CONFIGURATION</p>
          <a className={styles.menuItem}>
            <Shield size={18} className={styles.icon} />
            <span>Admin</span>
          </a>
          <a className={styles.menuItem}>
            <Settings size={18} className={styles.icon} />
            <span>Settings</span>
          </a>
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
