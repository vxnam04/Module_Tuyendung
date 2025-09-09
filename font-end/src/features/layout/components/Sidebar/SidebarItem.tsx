// SidebarItem.tsx
import React from "react";
import styles from "./Sidebar.module.css";

interface SidebarItemProps {
  icon: React.ReactNode;
  label: string;
  moduleUrl?: string; // Nếu dynamic import
}

export default function SidebarItem({
  icon,
  label,
  moduleUrl,
}: SidebarItemProps) {
  const handleClick = (e: React.MouseEvent) => {
    e.preventDefault();

    const container = document.getElementById("main-container");
    if (!container) return;

    // Nhúng iframe
    container.innerHTML = `<iframe src="${moduleUrl}" style="width:100%;height:100%;border:none"></iframe>`;

    // Hoặc nếu module JS: React.lazy + dynamic import
    // const Module = React.lazy(() => import(moduleUrl));
    // ReactDOM.render(<Module />, container);
  };

  return (
    <a className={styles.menuItem} href="#" onClick={handleClick}>
      {icon}
      <span>{label}</span>
    </a>
  );
}
