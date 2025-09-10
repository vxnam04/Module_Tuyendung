export const fetchWithAuth = async (url: string, options: RequestInit = {}) => {
  if (typeof window === "undefined")
    throw new Error("Không thể dùng localStorage trên server");

  const token = localStorage.getItem("token");
  if (!token) throw new Error("Chưa có token");

  const res = await fetch(url, {
    ...options,
    headers: {
      ...options.headers,
      Authorization: `Bearer ${token}`, // Bắt buộc 'Bearer <token>'
      "Content-Type": "application/json",
    },
  });

  if (!res.ok) throw new Error(`Lỗi: ${res.status}`);
  return res.json();
};
