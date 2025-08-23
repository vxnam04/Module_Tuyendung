"use client";

import { useState, useEffect } from "react";
import TeacherLayout from "../page";

export default function UsersPage() {
  const [data, setData] = useState("");

  useEffect(() => {
    fetch("http://localhost:8020/api/ping")
      .then((res) => res.json())
      .then((data) => setData(data.message))
      .catch((err) => setData("Lỗi kết nối: " + err.message));
  }, []);

  return (
    <TeacherLayout>
      <div>Backend trả về: {data}</div>
    </TeacherLayout>
  );
}
