"use client";

import { useState, useEffect } from "react";
import Sidebar from "@/src/components/Layout/teacher/Sidebar/Sidebar";
import Navbar from "@/src/components/Layout/teacher/Navbar/Navbar";
import { useSearchParams } from "next/navigation";

export default function TeacherLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const [collapsed, setCollapsed] = useState(false);
  const searchParams = useSearchParams();

  useEffect(() => {
    const tokens = searchParams.get("token");
    if (tokens) {
      localStorage.setItem("token", tokens);
    }
  }, [searchParams]);

  return (
    <div className="flex min-h-screen">
      <Sidebar collapsed={collapsed} />
      <div className="flex flex-col flex-1">
        <Navbar collapsed={collapsed} setCollapsed={setCollapsed} />
        <main className="p-4">{children}</main>
      </div>
    </div>
  );
}
