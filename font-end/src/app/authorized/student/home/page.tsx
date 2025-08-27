"use client";

import { useState, useEffect } from "react";
import StudentLayout from "../page";
import styles from "./home.module.css";
import Image from "next/image";
import {
  Search,
  MapPin,
  ChevronLeft,
  ChevronRight,
  ChevronDown,
} from "lucide-react";

interface JobCategory {
  id: string;
  name: string;
}

export default function HomePage() {
  const [data, setData] = useState("");
  const [currentIndex, setCurrentIndex] = useState(0);
  const [jobCategories, setJobCategories] = useState<JobCategory[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // state cho phân trang danh mục
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 6;

  // Danh sách ảnh cho slide
  const slides = [
    "/images/background.png",
    "/images/banner.jpg",
    "/images/chuydoiso.jpg",
  ];

  // Fetch dữ liệu từ backend test
  useEffect(() => {
    fetch("http://localhost:8020/api/ping")
      .then((res) => res.json())
      .then((data) => setData(data.message))
      .catch((err) => setData("Lỗi kết nối: " + err.message));
  }, []);

  // Tự động chuyển ảnh slide
  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % slides.length);
    }, 300000);
    return () => clearInterval(interval);
  }, [slides.length]);

  // Xử lý khi click mũi tên slide
  const prevSlide = () => {
    setCurrentIndex((prev) => (prev - 1 + slides.length) % slides.length);
  };

  const nextSlide = () => {
    setCurrentIndex((prev) => (prev + 1) % slides.length);
  };

  // Fetch industries từ backend Laravel
  useEffect(() => {
    async function fetchIndustries() {
      try {
        const baseUrl =
          process.env.NEXT_PUBLIC_API_URL || "http://localhost:8020";

        const res = await fetch(`${baseUrl}/api/job-industries`);
        if (!res.ok) throw new Error("Failed to fetch industries");

        const data: { industry_name: string }[] = await res.json();
        const categories = data.map((item) => ({
          id: item.industry_name,
          name: item.industry_name,
        }));
        setJobCategories(categories);
      } catch (err) {
        console.error(err);
        setError("Không thể tải danh mục ngành nghề");
      } finally {
        setLoading(false);
      }
    }
    fetchIndustries();
  }, []);

  // phân trang danh mục
  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = jobCategories.slice(indexOfFirstItem, indexOfLastItem);
  const totalPages = Math.ceil(jobCategories.length / itemsPerPage);

  const handlePrevPage = () => {
    setCurrentPage((prev) => (prev > 1 ? prev - 1 : prev));
  };

  const handleNextPage = () => {
    setCurrentPage((prev) => (prev < totalPages ? prev + 1 : prev));
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
            {/* <button className={styles.categoryBtn}>
              <List size={18} />
              <span>Danh mục Nghề</span>
            </button> */}
            <input
              type="text"
              placeholder="Vị trí tuyển dụng, tên công ty"
              className={styles.inputField}
            />
            <button className={styles.locationBtn}>
              <span className={styles.display}>
                <MapPin size={18} className={styles.itemlocation} />
                <span>Địa điểm</span>
              </span>
              <span className={styles.dropdown}>
                <ChevronDown size={16} className={styles.itemArrow} />
              </span>
            </button>
            <button className={styles.searchBtn}>
              <Search size={18} />
              <span>Tìm kiếm</span>
            </button>
          </div>

          {/* Danh mục ngành nghề */}
          <div className={styles.megaColumn}>
            <h3>VIỆC LÀM THEO NGÀNH NGHỀ</h3>
            <ul className={styles.gridMenu}>
              {loading ? (
                <li>Loading...</li>
              ) : error ? (
                <li>{error}</li>
              ) : (
                currentItems.map((cat) => (
                  <li key={cat.id} className={styles.listItem}>
                    <a href={`/jobs?category=${encodeURIComponent(cat.id)}`}>
                      <span>{cat.name}</span>
                      <ChevronRight size={16} className={styles.itemArrow} />
                    </a>
                  </li>
                ))
              )}
            </ul>

            {/* Phân trang */}
            {!loading && !error && (
              <div className={styles.pagination}>
                <button onClick={handlePrevPage} disabled={currentPage === 1}>
                  &lt;
                </button>
                <span>
                  {currentPage}/{totalPages}
                </span>
                <button
                  onClick={handleNextPage}
                  disabled={currentPage === totalPages}
                >
                  &gt;
                </button>
              </div>
            )}
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
