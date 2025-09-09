"use client";

import React from "react";
import { useAuth } from "../../auth/contexts/AuthContext";
import styles from "./DashboardHeader.module.css";

export const DashboardHeader: React.FC = () => {
  const { user, logout } = useAuth();

  const handleLogout = async () => {
    await logout();
  };

  const getUserTypeLabel = (user: any) => {
    if (user.user_type === "student") {
      return "Sinh viên";
    } else if (user.user_type === "lecturer") {
      return user.is_admin ? "Quản trị viên" : "Giảng viên";
    } else {
      return "Người dùng";
    }
  };

  if (!user) return null;

  return (
    <header className={styles.header}>
      <div className={styles.container}>
        <div className={styles.logo}>
          <h1>HPC Project</h1>
          <span className={styles.subtitle}>Hệ Thống Quản Lý Giáo Dục</span>
        </div>

        <div className={styles.userInfo}>
          <div className={styles.userDetails}>
            <span className={styles.userName}>{user.full_name}</span>
            <span className={styles.userType}>{getUserTypeLabel(user)}</span>
            <span className={styles.userCode}>
              {user.student_code || user.lecturer_code}
            </span>
          </div>

          <button onClick={handleLogout} className={styles.logoutButton}>
            Đăng xuất
          </button>
        </div>
      </div>
    </header>
  );
};
