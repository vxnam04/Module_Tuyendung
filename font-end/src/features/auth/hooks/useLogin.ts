"use client";

import { useState } from "react";
import { useAuth } from "../contexts/AuthContext";
import { LoginRequest } from "@/lib/api";
import { useRouter } from "next/navigation";

interface UseLoginReturn {
  isLoading: boolean;
  error: string | null;
  handleLogin: (credentials: LoginRequest) => Promise<void>;
  clearError: () => void;
}

export const useLogin = (): UseLoginReturn => {
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const { login, isLoggingIn } = useAuth();
  const router = useRouter();

  const handleLogin = async (credentials: LoginRequest) => {
    try {
      setIsLoading(true);
      setError(null);

      const response = await login(credentials);

      // Handle both formats: response.data OR response.user
      const userData = response.data || response.user;
      if (userData) {
        console.log("Login successful, user data:", userData);
        console.log("User type:", userData.user_type);

        // Debug logging
        console.log("useLogin - User data:", userData);
        console.log("useLogin - User type:", userData.user_type);
        console.log("useLogin - Is admin:", userData.is_admin);
        console.log("useLogin - Is admin type:", typeof userData.is_admin);
        console.log("useLogin - User data keys:", Object.keys(userData));

        // Redirect based on user type và is_admin
        let redirectPath = "/authorized/dashboard";
        if (userData.user_type === "student") {
          redirectPath = "/authorized/student/dashboard";
        } else if (userData.user_type === "lecturer") {
          // Handle both boolean and string values for is_admin
          const isAdmin = Boolean(userData.account?.is_admin);
          console.log("useLogin - Is admin (parsed):", isAdmin);

          if (isAdmin) {
            redirectPath = "/authorized/admin/dashboard"; // Lecturer + is_admin = Admin
            console.log("useLogin - Redirecting to ADMIN dashboard");
          } else {
            redirectPath = "/authorized/lecturer/dashboard"; // Lecturer + !is_admin = Giáo viên
            console.log("useLogin - Redirecting to LECTURER dashboard");
          }
        }

        console.log("Redirecting to:", redirectPath);

        // Try router.push first
        try {
          router.push(redirectPath);
          console.log("Router.push executed successfully");
        } catch (routerError) {
          console.error("Router.push failed:", routerError);
          // Fallback to window.location
          window.location.href = redirectPath;
        }
      }
    } catch (err: any) {
      setError(err.message || "Đăng nhập thất bại. Vui lòng thử lại.");
    } finally {
      setIsLoading(false);
    }
  };

  // Use global loading state from context
  const finalIsLoading = isLoading || isLoggingIn;

  const clearError = () => {
    setError(null);
  };

  return {
    isLoading: finalIsLoading,
    error,
    handleLogin,
    clearError,
  };
};
