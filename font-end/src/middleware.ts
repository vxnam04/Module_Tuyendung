import { NextResponse } from "next/server";
import type { NextRequest } from "next/server";
import { jwtVerify, JWTPayload } from "jose";

// Định nghĩa payload cho JWT
interface CustomJWTPayload extends JWTPayload {
  role: "admin" | "giaovien" | "sinhvien";
}

// Middleware
export async function middleware(request: NextRequest) {
  const token = request.cookies.get("token")?.value;

  // Nếu chưa đăng nhập → chuyển về login
  if (!token) {
    return NextResponse.redirect(new URL("/login", request.url));
  }

  try {
    // Giải mã token
    const secret = new TextEncoder().encode(process.env.JWT_SECRET!);
    const { payload } = await jwtVerify(token, secret);
    const user = payload as CustomJWTPayload;

    // Kiểm tra quyền
    if (
      request.nextUrl.pathname.startsWith("/admin") &&
      user.role !== "admin"
    ) {
      return NextResponse.redirect(new URL("/403", request.url));
    }

    if (
      request.nextUrl.pathname.startsWith("/giaovien") &&
      user.role !== "giaovien"
    ) {
      return NextResponse.redirect(new URL("/403", request.url));
    }

    if (
      request.nextUrl.pathname.startsWith("/sinhvien") &&
      user.role !== "sinhvien"
    ) {
      return NextResponse.redirect(new URL("/403", request.url));
    }
  } catch (error) {
    console.error("JWT verify error:", error);
    return NextResponse.redirect(new URL("/login", request.url));
  }

  return NextResponse.next();
}

// Cấu hình middleware cho các route cần bảo vệ
export const config = {
  matcher: ["/admin/:path*", "/giaovien/:path*", "/sinhvien/:path*"],
};
