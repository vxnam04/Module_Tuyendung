"use client";

import React, { ReactNode } from "react";
import { useAuth } from "../contexts/AuthContext";
import { useRouter } from "next/navigation";
import { useEffect } from "react";

interface ProtectedRouteProps {
  children: ReactNode;
  requiredUserType?: "student" | "lecturer" | "admin";
  fallback?: ReactNode;
}

export const ProtectedRoute: React.FC<ProtectedRouteProps> = ({
  children,
  requiredUserType,
  fallback,
}) => {
  const { isAuthenticated, user, isLoading } = useAuth();
  const router = useRouter();

  useEffect(() => {
    if (!isLoading && !isAuthenticated) {
      router.push("/auth/login");
      return;
    }

    if (requiredUserType && user) {
      // Logic phân quyền mới: student, lecturer, admin
      let actualRole: "student" | "lecturer" | "admin";
      if (user.user_type === "student") {
        actualRole = "student";
      } else if (user.user_type === "lecturer") {
        // Handle both boolean and string values for is_admin
        const isAdmin = Boolean(user.account?.is_admin);
        actualRole = isAdmin ? "admin" : "lecturer";
      } else {
        actualRole = "lecturer"; // fallback
      }

      if (actualRole !== requiredUserType) {
        // Redirect to appropriate dashboard based on actual role
        switch (actualRole) {
          case "student":
            router.push("/authorized/student/dashboard");
            break;
          case "lecturer":
            router.push("/authorized/lecturer/dashboard");
            break;
          case "admin":
            router.push("/authorized/admin/dashboard");
            break;
          default:
            router.push("/authorized/dashboard");
        }
      }
    }
  }, [isAuthenticated, user, isLoading, requiredUserType, router]);

  if (isLoading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  if (!isAuthenticated) {
    return fallback || null;
  }

  if (requiredUserType && user) {
    // Debug logging
    console.log("ProtectedRoute - User data:", user);
    console.log("ProtectedRoute - User type:", user.user_type);
    console.log("ProtectedRoute - Is admin:", user.account?.is_admin);
    console.log("ProtectedRoute - Account data:", user.account);
    console.log("ProtectedRoute - Required type:", requiredUserType);

    // Logic phân quyền mới: student, lecturer, admin
    let actualRole: "student" | "lecturer" | "admin";
    if (user.user_type === "student") {
      actualRole = "student";
    } else if (user.user_type === "lecturer") {
      actualRole = user.account?.is_admin ? "admin" : "lecturer";
    } else {
      actualRole = "lecturer"; // fallback
    }

    console.log("ProtectedRoute - Actual role:", actualRole);
    console.log(
      "ProtectedRoute - Role match:",
      actualRole === requiredUserType
    );

    if (actualRole !== requiredUserType) {
      console.log("ProtectedRoute - Access denied, redirecting...");
      return fallback || null;
    }
  }

  return <>{children}</>;
};
