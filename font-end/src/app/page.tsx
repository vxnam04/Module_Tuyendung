"use client";

import { useEffect } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { jwtDecode } from "jwt-decode";

interface TokenPayload {
  sub: number;
  user_type: string; // 'student' hoặc 'lecturer'
  username: string;
  email: string;
  full_name: string;
  iat: number;
  exp: number;
}

export default function HomePage() {
  const router = useRouter();
  const searchParams = useSearchParams();

  useEffect(() => {
    const urlToken = searchParams.get("token");

    // Nếu có token từ URL, lưu vào localStorage
    if (urlToken) {
      localStorage.setItem("token", urlToken);
    }

    // Lấy token từ URL hoặc localStorage
    const token = urlToken || localStorage.getItem("token");

    if (!token) {
      router.replace("http://localhost:3001/auth/login");
      return;
    }

    try {
      const decoded: TokenPayload = jwtDecode(token);

      // Kiểm tra user_type để redirect
      if (decoded.user_type === "student") {
        router.replace("/authorized/student/home");
      } else if (decoded.user_type === "lecturer") {
        router.replace("/authorized/teacher/documents");
      } else {
        router.replace("http://localhost:3001/auth/login");
      }
    } catch (err) {
      console.error("Token không hợp lệ:", err);
      localStorage.removeItem("token"); // xóa token lỗi
      router.replace("http://localhost:3001/auth/login");
    }
  }, [router, searchParams]);

  return (
    <div className="flex items-center justify-center h-screen">
      <p>Đang kiểm tra quyền truy cập...</p>
    </div>
  );
}
