// src/lib/apiClient.ts

const API_BASE_URL = "http://localhost:8020"; // đổi theo Laravel backend

export interface ApiResponse<T = unknown> {
  data: T | null;
  error?: string;
}

export interface LoginRequest {
  username: string;
  password: string;
  user_type: "student" | "lecturer" | "admin";
}

export interface UserProfile {
  id: number;
  name: string;
  email: string;
  user_type: "student" | "lecturer" | "admin";
  account?: {
    is_admin?: boolean;
  };
}

export interface LoginResponse {
  token: string;
  user: UserProfile;
}

let authToken: string | null =
  typeof window !== "undefined" ? localStorage.getItem("token") : null;

const setToken = (token: string) => {
  authToken = token;
  if (typeof window !== "undefined") {
    localStorage.setItem("token", token);
  }
};

const clearToken = () => {
  authToken = null;
  if (typeof window !== "undefined") {
    localStorage.removeItem("token");
  }
};

const getToken = () => authToken;

export const apiClient = {
  request: async <T>(
    endpoint: string,
    options: RequestInit = {}
  ): Promise<ApiResponse<T>> => {
    const token = getToken();

    const headers: HeadersInit = {
      "Content-Type": "application/json",
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    };

    try {
      const res = await fetch(`${API_BASE_URL}${endpoint}`, {
        ...options,
        headers,
      });
      const data = (await res.json().catch(() => null)) as T | null;

      if (!res.ok) {
        return {
          data: null,
          error:
            (data as { message?: string })?.message || "API request failed",
        };
      }

      return { data };
    } catch (err) {
      const error = err instanceof Error ? err.message : "Network error";
      return { data: null, error };
    }
  },

  login: async (
    credentials: LoginRequest
  ): Promise<ApiResponse<LoginResponse>> => {
    const res = await apiClient.request<LoginResponse>("/api/login", {
      method: "POST",
      body: JSON.stringify(credentials),
    });

    if (res.data?.token) {
      setToken(res.data.token);
    }

    return res;
  },

  logout: async (): Promise<void> => {
    try {
      await apiClient.request("/api/logout", { method: "POST" });
    } finally {
      clearToken();
    }
  },

  getProfile: async (): Promise<ApiResponse<UserProfile>> => {
    return apiClient.request<UserProfile>("/api/me");
  },

  isAuthenticated: (): boolean => !!getToken(),
  clearToken,
};
