"use client";

import Navbar from "@/src/components/Layout/student/Navbar/Navbar";

export default function StudentLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <div className="flex min-h-screen">
      <div className="flex flex-col flex-1">
        <Navbar />
        <main>{children}</main>
      </div>
    </div>
  );
}
