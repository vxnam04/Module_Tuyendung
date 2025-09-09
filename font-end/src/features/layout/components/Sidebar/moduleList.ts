// Đây là file điều hướng module khi làm mô hình microsevice
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

// Kiểu dữ liệu của module
export interface ModuleConfig {
  id: string; // unique id
  label: string; // tên hiển thị
  icon: React.ReactNode;
  type?: "iframe" | "react" | "angular" | "vue"; // loại module
  moduleUrl: string; // URL hoặc đường dẫn module
  group?: "top" | "bottom"; // vị trí sidebar
}

export const modules: ModuleConfig[] = [
  //   // Dashboard + tasks
  //   { id: "dashboard", label: "Analytics", icon: <LayoutDashboard size={18} />, type: "iframe", moduleUrl: "https://dashboard.company.com", group: "top" },
  //   { id: "documents", label: "Documents", icon: <FileText size={18} />, type: "iframe", moduleUrl: "https://documents.company.com", group: "top" },
  //   { id: "calendar", label: "Calendar", icon: <Calendar size={18} />, type: "iframe", moduleUrl: "https://calendar.company.com", group: "top" },
  //   { id: "notifications", label: "Notifications", icon: <Bell size={18} />, type: "iframe", moduleUrl: "https://notifications.company.com", group: "top" },
  //   { id: "tasks", label: "Tasks", icon: <CheckSquare size={18} />, type: "iframe", moduleUrl: "https://tasks.company.com", group: "top" },
  //   // Relationships
  //   { id: "departments", label: "Departments", icon: <Users size={18} />, type: "iframe", moduleUrl: "https://departments.company.com", group: "top" },
  //   { id: "blog", label: "Blog", icon: <BookOpen size={18} />, type: "iframe", moduleUrl: "https://blog.company.com", group: "top" },
  //   { id: "chats", label: "Chats", icon: <MessageSquare size={18} />, type: "iframe", moduleUrl: "https://chats.company.com", group: "top" },
  //   // Configuration
  //   { id: "admin", label: "Admin", icon: <Shield size={18} />, type: "iframe", moduleUrl: "https://admin.company.com", group: "top" },
  //   { id: "settings", label: "Settings", icon: <Settings size={18} />, type: "iframe", moduleUrl: "https://settings.company.com", group: "top" },
  //   // Bottom menu
  //   { id: "support", label: "Support", icon: <HelpCircle size={18} />, type: "iframe", moduleUrl: "https://support.company.com", group: "bottom" },
  //   { id: "logout", label: "Logout", icon: <LogOut size={18} />, type: "link", moduleUrl: "/logout", group: "bottom" },
];
