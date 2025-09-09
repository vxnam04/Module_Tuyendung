"use client";

import Link from "next/link";
import { Container } from "react-bootstrap";

export default function Header() {
  return (
    <header className="header sticky-top mb-4 py-2 px-sm-2 border-bottom">
      <Container fluid className="header-navbar d-flex align-items-center px-0">
        <Link href="/" className="header-brand d-md-none">
          <h4>HPC Project</h4>
        </Link>
        <div className="header-nav d-none d-md-flex">
          <span className="nav-link">Dashboard</span>
        </div>
        <div className="header-nav ms-auto">
          <span className="nav-link">Thông báo</span>
        </div>
        <div className="header-nav ms-2">
          <span className="nav-link">Hồ sơ</span>
        </div>
      </Container>
    </header>
  );
}
