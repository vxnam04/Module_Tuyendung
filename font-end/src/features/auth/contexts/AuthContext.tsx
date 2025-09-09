"use client";

import React, {
  createContext,
  useContext,
  useEffect,
  useState,
  ReactNode,
} from "react";
import {
  apiClient,
  UserProfile,
  LoginRequest,
  ApiResponse,
} from "../../../lib/apiClient";

// LoginResponse để match với API backend trả về
export interface LoginResponse {
  token: string;
  user: UserProfile;
}

interface AuthContextType {
  user: UserProfile | null;
  userRole: "student" | "lecturer" | "admin" | null;
  isAuthenticated: boolean;
  isAdmin: boolean;
  isLecturer: boolean;
  isStudent: boolean;
  isLoading: boolean;
  isLoggingIn: boolean;
  login: (credentials: LoginRequest) => Promise<ApiResponse<LoginResponse>>;
  logout: () => Promise<void>;
  refreshUser: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error("useAuth must be used within an AuthProvider");
  }
  return context;
};

interface AuthProviderProps {
  children: ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  const [user, setUser] = useState<UserProfile | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [isLoggingIn, setIsLoggingIn] = useState(false);

  // Xác định role từ user
  const userRole = user
    ? user.user_type === "student"
      ? "student"
      : Boolean(user.account?.is_admin)
      ? "admin"
      : "lecturer"
    : null;
  const isAuthenticated = !!user;
  const isAdmin = userRole === "admin";
  const isLecturer = userRole === "lecturer";
  const isStudent = userRole === "student";

  // Check authentication khi mount app
  useEffect(() => {
    const checkAuth = async () => {
      try {
        if (apiClient.isAuthenticated()) {
          const response = await apiClient.getProfile();
          if (response.data) {
            setUser(response.data);
          }
        }
      } catch (error) {
        console.error("Failed to check authentication:", error);
        apiClient.clearToken();
      } finally {
        setIsLoading(false);
      }
    };

    checkAuth();
  }, []);

  // Login
  const login = async (
    credentials: LoginRequest
  ): Promise<ApiResponse<LoginResponse>> => {
    try {
      setIsLoggingIn(true);
      const response = await apiClient.login(credentials);

      console.log("AuthContext - Login response:", response);

      const userData = response.data?.user ?? null;
      if (userData) {
        setUser(userData);
        console.log("AuthContext - User set successfully:", userData);
      } else {
        console.log("AuthContext - No user data in response");
      }

      return response;
    } catch (error) {
      console.error("AuthContext - Login error:", error);
      throw error;
    } finally {
      setIsLoggingIn(false);
    }
  };

  // Logout
  const logout = async (): Promise<void> => {
    try {
      await apiClient.logout();
    } catch (error) {
      console.error("Logout error:", error);
    } finally {
      setUser(null);
      apiClient.clearToken();
    }
  };

  // Refresh user profile
  const refreshUser = async (): Promise<void> => {
    try {
      const response = await apiClient.getProfile();
      if (response.data) {
        setUser(response.data);
      }
    } catch (error) {
      console.error("Failed to refresh user:", error);
      await logout();
    }
  };

  const value: AuthContextType = {
    user,
    userRole,
    isAuthenticated,
    isAdmin,
    isLecturer,
    isStudent,
    isLoading,
    isLoggingIn,
    login,
    logout,
    refreshUser,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};
