import Link from "next/link";

export default function NotFound() {
  return (
    <div className="flex flex-col items-center justify-center h-screen text-center">
      <h2 className="text-2xl font-bold mb-4">404 - Trang không tồn tại</h2>
      <p className="mb-4">Xin lỗi, trang bạn đang tìm không tồn tại.</p>
      <Link
        href="/"
        className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Quay lại Trang chủ
      </Link>
    </div>
  );
}
