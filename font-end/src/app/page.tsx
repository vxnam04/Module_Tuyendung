"use client";
import { useEffect, useState, useCallback } from "react";
import { useRouter } from "next/navigation";

export default function AuthListener() {
  const router = useRouter();
  const [token, setToken] = useState<string | null>(null);

  // Memoize fetchUser so it doesn't recreate on every render
  const fetchUser = useCallback(
    async (token: string) => {
      try {
        const res = await fetch("http://localhost:8090/api/me", {
          headers: { Authorization: `Bearer ${token}` },
        });
        if (!res.ok) throw new Error("Token không hợp lệ");

        const data = await res.json();
        console.log("✅ User data:", data);

        if (data.role === "student") router.push("/student");
        else if (data.role === "lecturer") router.push("/lecturer");
        else if (data.role === "admin") router.push("/admin");
        else router.push("/authorized");
      } catch (err) {
        console.error(err);
        localStorage.removeItem("token");
        alert("Token không hợp lệ, vui lòng đăng nhập lại");
      }
    },
    [router]
  );

  useEffect(() => {
    // Lắng nghe token từ popup app 3001
    const handleMessage = (event: MessageEvent) => {
      if (event.origin !== "http://localhost:3001") return; // bảo mật
      const { token } = event.data;
      if (token) {
        console.log("✅ Token received from popup:", token);
        localStorage.setItem("token", token);
        setToken(token);
        fetchUser(token);
      }
    };
    window.addEventListener("message", handleMessage);

    // Nếu đã có token sẵn
    const existingToken = localStorage.getItem("token");
    if (existingToken) setToken(existingToken);

    return () => window.removeEventListener("message", handleMessage);
  }, [fetchUser]); // now safe to include fetchUser here

  const openLoginPopup = () => {
    window.open(
      "http://localhost:3001/auth/login",
      "LoginPopup",
      "width=500,height=700"
    );
  };

  return (
    <div>
      {token ? (
        <p>Đang xác thực người dùng...</p>
      ) : (
        <>
          <p>Chưa đăng nhập</p>
          <button onClick={openLoginPopup} className="btn">
            Đăng nhập
          </button>
        </>
      )}
    </div>
  );
}
