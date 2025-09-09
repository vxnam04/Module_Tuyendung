// Application Configuration
export const APP_CONFIG = {
  name: process.env.NEXT_PUBLIC_APP_NAME || "HPC Project",
  description:
    process.env.NEXT_PUBLIC_APP_DESCRIPTION || "Hệ Thống Quản Lý Giáo Dục",
  version: "1.0.0",
} as const;

// API Configuration
export const API_CONFIG = {
  baseUrl: process.env.NEXT_PUBLIC_API_URL || "http://localhost:8090/api",
  timeout: 10000, // 10 seconds
  endpoints: {
    students: {
      list: "/v1/students",
      profile: "/v1/student/profile",
    },
    lecturers: {
      list: "/v1/lecturers",
      profile: "/v1/lecturer/profile",
    },
    classes: {
      list: "/v1/classes",
    },
    departments: {
      list: "/v1/departments",
      tree: "/v1/departments/tree",
    },
  },
} as const;

// User Types
export const USER_TYPES = {
  STUDENT: "student",
  LECTURER: "lecturer",
  ADMIN: "admin",
} as const;

// Routes
export const ROUTES = {
  auth: {
    login: "/auth/login",
    register: "/auth/register",
  },
  dashboard: {
    main: "/authorized/dashboard",
    student: "/authorized/student/dashboard",
    lecturer: "/authorized/lecturer/dashboard",
    admin: "/authorized/admin/dashboard",
  },
} as const;

// Local Storage Keys
export const STORAGE_KEYS = {
  AUTH_TOKEN: "auth_token",
  USER_PREFERENCES: "user_preferences",
  THEME: "theme",
} as const;
