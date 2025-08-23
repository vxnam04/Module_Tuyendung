"use client";

import { useState, useEffect } from "react";
import StudentLayout from "../page";
import styles from "./home.module.css";
import Image from "next/image";

export default function HomePage() {
  const [data, setData] = useState("");

  useEffect(() => {
    fetch("http://localhost:8020/api/ping")
      .then((res) => res.json())
      .then((data) => setData(data.message))
      .catch((err) => setData("Lỗi kết nối: " + err.message));
  }, []);

  return (
    <div className={styles.container}>
      <StudentLayout>
        <div className={styles.banner1}>
          <Image
            className={styles.banner}
            src="/images/banner.jpg"
            alt="Mô tả ảnh"
            fill
            style={{
              objectFit: "cover",
              objectPosition: "50% 10%", // ngang giữa (50%), dọc lệch lên trên (20%)
            }}
          />
        </div>
        <div>Backend trả về: {data}</div>
      </StudentLayout>
    </div>
  );
}
