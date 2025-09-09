"use client";

import { useState, useEffect } from "react";
import StudentLayout from "../page";
import styles from "./home.module.css";
import Image from "next/image";
import JobList from "../job/job_list/job_list";
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

// Backend trả về mỗi item 1 city
interface RawLocation {
  state: string;
  city: string;
}

// Mảng đã gom cities
interface Location {
  state: string;
  cities: string[];
}

export default function HomePage() {
  // Backend ping
  const [data, setData] = useState("");
  useEffect(() => {
    fetch("http://localhost:8020/api/ping")
      .then((res) => res.json())
      .then((data) => setData(data.message))
      .catch((err) => setData("Lỗi kết nối: " + err.message));
  }, []);

  // Slide
  const slides = [
    "/images/background.png",
    "/images/banner.jpg",
    "/images/chuydoiso.jpg",
  ];
  const [currentIndex, setCurrentIndex] = useState(0);

  useEffect(() => {
    const interval = setInterval(
      () => setCurrentIndex((prev) => (prev + 1) % slides.length),
      3000
    );
    return () => clearInterval(interval);
  }, [slides.length]);

  const prevSlide = () =>
    setCurrentIndex((prev) => (prev - 1 + slides.length) % slides.length);
  const nextSlide = () => setCurrentIndex((prev) => (prev + 1) % slides.length);

  // Job categories
  const [jobCategories, setJobCategories] = useState<JobCategory[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 6;

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

  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = jobCategories.slice(indexOfFirstItem, indexOfLastItem);
  const totalPages = Math.ceil(jobCategories.length / itemsPerPage);

  const handlePrevPage = () =>
    setCurrentPage((prev) => (prev > 1 ? prev - 1 : prev));
  const handleNextPage = () =>
    setCurrentPage((prev) => (prev < totalPages ? prev + 1 : prev));

  // Location
  const [showLocationBox, setShowLocationBox] = useState(false);
  const [locations, setLocations] = useState<Location[]>([]);
  const [loadingLocations, setLoadingLocations] = useState(false);
  const [selectedState, setSelectedState] = useState<string | null>(null);
  const [selectedCities, setSelectedCities] = useState<string[]>([]);

  const fetchLocations = async () => {
    try {
      setLoadingLocations(true);
      const baseUrl =
        process.env.NEXT_PUBLIC_API_URL || "http://localhost:8020";
      const res = await fetch(`${baseUrl}/api/locations`);
      if (!res.ok) throw new Error("Failed to fetch locations");
      const data: RawLocation[] = await res.json();

      // Gom cities theo state, loại trùng
      const grouped: Location[] = [];
      data.forEach((item) => {
        const stateObj = grouped.find((s) => s.state === item.state);
        if (stateObj) {
          if (!stateObj.cities.includes(item.city)) {
            stateObj.cities.push(item.city);
          }
        } else {
          grouped.push({ state: item.state, cities: [item.city] });
        }
      });
      setLocations(grouped);
    } catch (err) {
      console.error("Fetch locations error:", err);
    } finally {
      setLoadingLocations(false);
    }
  };

  const handleSelectCity = (city: string) => {
    setSelectedCities((prev) =>
      prev.includes(city) ? prev.filter((c) => c !== city) : [...prev, city]
    );
  };

  const handleSelectAll = () => {
    if (!selectedState) return;
    const allCities =
      locations.find((s) => s.state === selectedState)?.cities || [];
    if (selectedCities.length === allCities.length) {
      setSelectedCities([]);
    } else {
      setSelectedCities(allCities);
    }
  };

  const allCities = selectedState
    ? locations.find((loc) => loc.state === selectedState)?.cities || []
    : [];

  return (
    <div className={styles.container}>
      <StudentLayout>
        {/* Banner */}
        <div className={styles.banner1}>
          <Image
            className={styles.banner}
            src="/images/banner.jpg"
            alt="Mô tả ảnh"
            fill
            style={{ objectFit: "cover", objectPosition: "50% 10%" }}
          />

          {/* Search Box */}
          <div className={styles.searchBox}>
            <input
              type="text"
              placeholder="Vị trí tuyển dụng, tên công ty"
              className={styles.inputField}
            />

            <button
              className={styles.locationBtn}
              onClick={() => {
                setShowLocationBox(!showLocationBox);
                if (!showLocationBox) fetchLocations();
              }}
            >
              <span className={styles.display}>
                <MapPin size={18} className={styles.itemlocation} />
                <span>Địa điểm</span>
              </span>
              <span className={styles.dropdown}>
                <ChevronDown size={16} className={styles.itemArrow} />
              </span>
            </button>

            {showLocationBox && (
              <div className={styles.locationBox}>
                <div className={styles.box}>
                  {/* State */}
                  <div className={styles.column}>
                    <h4>Tỉnh/Thành phố</h4>
                    {loadingLocations ? (
                      <p>Đang tải...</p>
                    ) : (
                      <ul>
                        {locations.map((loc) => (
                          <li key={loc.state}>
                            <label className={styles.text}>
                              <input
                                type="radio"
                                name="state"
                                checked={selectedState === loc.state}
                                onChange={() => {
                                  setSelectedState(loc.state);
                                  setSelectedCities([]);
                                }}
                                className={styles.squareRadio}
                              />

                              {loc.state}
                            </label>
                          </li>
                        ))}
                      </ul>
                    )}
                  </div>

                  {/* City */}
                  <div className={styles.column}>
                    <h4>Quận/Huyện</h4>
                    {selectedState ? (
                      allCities.length > 0 ? (
                        <ul>
                          <li>
                            <label>
                              <input
                                type="checkbox"
                                checked={
                                  selectedCities.length === allCities.length
                                }
                                onChange={handleSelectAll}
                                className={styles.squareRadio}
                              />
                              Tất cả
                            </label>
                          </li>
                          {allCities.map((city) => (
                            <li key={`${selectedState}-${city}`}>
                              <label>
                                <input
                                  type="checkbox"
                                  checked={selectedCities.includes(city)}
                                  onChange={() => handleSelectCity(city)}
                                  className={styles.squareRadio}
                                />
                                {city}
                              </label>
                            </li>
                          ))}
                        </ul>
                      ) : (
                        <p>Không có Quận/Huyện</p>
                      )
                    ) : (
                      <p>Chọn Tỉnh/Thành phố trước</p>
                    )}
                  </div>
                </div>

                <div className={styles.footer}>
                  <button onClick={() => setSelectedCities([])}>
                    Bỏ chọn tất cả
                  </button>
                  <button
                    className={styles.applyBtn}
                    onClick={() => setShowLocationBox(false)}
                  >
                    Áp dụng
                  </button>
                </div>
              </div>
            )}

            <button className={styles.searchBtn}>
              <Search size={18} />
              <span>Tìm kiếm</span>
            </button>
          </div>

          {/* Mega Column ngành nghề */}
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

          {/* Slide */}
          <div className={styles.slideBox}>
            <Image
              key={currentIndex}
              src={slides[currentIndex]}
              alt="Slide"
              fill
              priority
              className={styles.slideImage}
            />
            <button
              className={`${styles.arrowBtn} ${styles.left}`}
              onClick={prevSlide}
            >
              <ChevronLeft size={28} />
            </button>
            <button
              className={`${styles.arrowBtn} ${styles.right}`}
              onClick={nextSlide}
            >
              <ChevronRight size={28} />
            </button>
          </div>
        </div>
        <div>
          <JobList />
        </div>

        <div>Backend trả về: {data}</div>
      </StudentLayout>
    </div>
  );
}
