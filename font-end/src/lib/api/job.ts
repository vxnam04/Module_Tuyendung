export async function getJobs() {
  const baseUrl = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8020";

  const token = localStorage.getItem("token"); // Lấy token lưu khi login

  const res = await fetch(`${baseUrl}/job-posts`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
    },
    cache: "no-store", // tắt cache để luôn fetch mới
  });

  if (!res.ok) throw new Error("Failed to fetch jobs");
  return res.json();
}

export async function getJobById(id: number) {
  const baseUrl = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8020";

  const token = localStorage.getItem("token");

  const res = await fetch(`${baseUrl}/job-posts/${id}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
    },
  });

  if (!res.ok) throw new Error("Failed to fetch job");
  return res.json();
}
