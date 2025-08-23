"use client";

import { useState, useEffect } from "react";
import StudentLayout from "../page";
import styles from "./home.module.css";
import Image from "next/image";
import { Search, List, MapPin, ChevronLeft, ChevronRight } from "lucide-react";

export default function HomePage() {
  const [data, setData] = useState("");
  const [currentIndex, setCurrentIndex] = useState(0);

  // Danh sách ảnh cho slide
  const slides = [
    "/images/background.png",
    "/images/banner.jpg",
    "/images/chuydoiso.jpg",
  ];

  // Fetch dữ liệu từ backend
  useEffect(() => {
    fetch("http://localhost:8020/api/ping")
      .then((res) => res.json())
      .then((data) => setData(data.message))
      .catch((err) => setData("Lỗi kết nối: " + err.message));
  }, []);

  // Tự động chuyển ảnh
  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % slides.length);
    }, 3000000);
    return () => clearInterval(interval);
  }, [slides.length]);

  // Xử lý khi click mũi tên
  const prevSlide = () => {
    setCurrentIndex((prev) => (prev - 1 + slides.length) % slides.length);
  };

  const nextSlide = () => {
    setCurrentIndex((prev) => (prev + 1) % slides.length);
  };

  return (
    <div className={styles.container}>
      <StudentLayout>
        <div className={styles.banner1}>
          {/* Banner chính */}
          <Image
            className={styles.banner}
            src="/images/banner.jpg"
            alt="Mô tả ảnh"
            fill
            style={{
              objectFit: "cover",
              objectPosition: "50% 10%",
            }}
          />

          {/* Input Search Box */}
          <div className={styles.searchBox}>
            <button className={styles.categoryBtn}>
              <List size={18} />
              <span>Danh mục Nghề</span>
            </button>
            <input
              type="text"
              placeholder="Vị trí tuyển dụng, tên công ty"
              className={styles.inputField}
            />
            <button className={styles.locationBtn}>
              <MapPin size={18} />
              <span>Địa điểm</span>
              <span className={styles.dropdown}>▼</span>
            </button>
            <button className={styles.searchBtn}>
              <Search size={18} />
              <span>Tìm kiếm</span>
            </button>
          </div>

          {/* Box nhỏ chứa slide ảnh */}
          <div className={styles.slideBox}>
            <Image
              key={currentIndex}
              src={slides[currentIndex]}
              alt="Slide"
              fill
              priority
              className={styles.slideImage}
            />

            {/* Nút mũi tên trái */}
            <button
              className={`${styles.arrowBtn} ${styles.left}`}
              onClick={prevSlide}
            >
              <ChevronLeft size={28} />
            </button>

            {/* Nút mũi tên phải */}
            <button
              className={`${styles.arrowBtn} ${styles.right}`}
              onClick={nextSlide}
            >
              <ChevronRight size={28} />
            </button>
          </div>
        </div>

        <div>Backend trả về: {data}</div>
      </StudentLayout>
    </div>
  );
}
