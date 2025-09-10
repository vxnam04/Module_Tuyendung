"use client";

import { useState, useEffect } from "react";
import TeacherLayout from "../page";
import { fetchWithAuth } from "@/src/lib/fetchWithAuth";

export default function UsersPage() {
  const [data, setData] = useState<string>("Đang tải...");

  useEffect(() => {
    fetchWithAuth("http://localhost:8020/api/ping")
      .then((res) => setData(res.message)) // không cần res.json()
      .catch((err) => setData("Lỗi kết nối: " + err.message));
  }, []);

  return (
    <TeacherLayout>
      <div>Backend trả về: {data}</div>
    </TeacherLayout>
  );
}
