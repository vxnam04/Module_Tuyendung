// Auth Types
export interface UserProfile {
  id: number;
  full_name: string;
  student_code?: string;
  lecturer_code?: string;
  email: string;
  department: string;
  user_type: "student" | "lecturer";
  is_admin?: boolean;
  account?: {
    is_admin: boolean;
    username: string;
  };
}

export interface LoginRequest {
  username: string;
  password: string;
  user_type: "student" | "lecturer";
}

export interface LoginResponse {
  id: number;
  full_name: string;
  student_code?: string;
  lecturer_code?: string;
  email: string;
  department: string;
  token: string;
  user_type: "student" | "lecturer";
  is_admin?: boolean;
}
