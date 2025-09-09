"use client";
import { useEffect, useState } from "react";
import { getJobs } from "@/src/lib/api/job";

interface Job {
  id: number;
  title: string;
  company_name: string;
  salary_range: string;
  location: string;
}

export default function JobList() {
  const [jobs, setJobs] = useState<Job[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [selectedLocation, setSelectedLocation] = useState("Tất cả");

  useEffect(() => {
    getJobs()
      .then((data) => setJobs(data))
      .catch((err) => setError(err.message))
      .finally(() => setLoading(false));
  }, []);

  const filteredJobs =
    selectedLocation === "Tất cả"
      ? jobs
      : jobs.filter((job) => job.location?.includes(selectedLocation));

  if (loading) return <p className="p-6">Đang tải...</p>;
  if (error) return <p className="p-6 text-red-500">{error}</p>;

  return (
    <div className="p-6 max-w-6xl mx-auto">
      <h1 className="text-2xl font-bold text-green-600 mb-4">
        Việc làm tốt nhất
      </h1>

      {/* Bộ lọc */}
      <div className="flex gap-3 mb-6">
        {["Tất cả", "Hà Nội", "Hồ Chí Minh", "Đà Nẵng"].map((city) => (
          <button
            key={city}
            className={`px-4 py-2 rounded-full border ${
              selectedLocation === city
                ? "bg-green-600 text-white"
                : "bg-white text-gray-700"
            }`}
            onClick={() => setSelectedLocation(city)}
          >
            {city}
          </button>
        ))}
      </div>

      {/* Danh sách job */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {filteredJobs.map((job) => (
          <div
            key={job.id}
            className="border rounded-xl p-4 shadow hover:shadow-lg transition bg-white"
          >
            <h2 className="font-semibold text-lg">{job.title}</h2>
            <p className="text-gray-600 text-sm">{job.company_name}</p>
            <p className="mt-2 font-medium text-green-600">
              {job.salary_range}
            </p>
            <p className="text-gray-500">{job.location}</p>
            <button className="mt-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
              Ứng tuyển
            </button>
          </div>
        ))}
      </div>
    </div>
  );
}
