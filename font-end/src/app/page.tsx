"use client";
import { useEffect } from "react";
import { useRouter } from "next/navigation";

export default function SomePage() {
  const router = useRouter();

  useEffect(() => {
    const token = localStorage.getItem("token");
    if (!token) {
      router.push("/auth/login"); // redirect thẳng tới login
    }
  }, []);

  return <div>Protected Content</div>;
}
