# Features Directory Structure

## 🏗️ **Cấu Trúc Feature-Based Architecture**

Mỗi feature được tổ chức theo cấu trúc chuẩn để dễ quản lý và mở rộng:

### **📁 Auth Feature** (`/features/auth/`)

```
auth/
├── components/          # UI Components
│   ├── LoginForm.tsx
│   ├── ProtectedRoute.tsx
│   └── LoginForm.module.css
├── hooks/              # Custom Hooks
│   └── useLogin.ts
├── contexts/           # React Context
│   └── AuthContext.tsx
├── types/              # Type Definitions
│   └── index.ts
└── index.ts            # Public API Export
```

### **📁 Dashboard Feature** (`/features/dashboard/`)

```
dashboard/
├── components/          # Dashboard UI
│   ├── DashboardHeader.tsx
│   └── DashboardHeader.module.css
├── hooks/              # Dashboard Logic
├── types/              # Dashboard Types
└── index.ts            # Public API Export
```

### **📁 Layout Feature** (`/features/layout/`)

```
layout/
├── components/          # Layout Components
│   ├── Sidebar/
│   ├── Navbar/
│   ├── Header/
│   ├── Footer/
│   └── Breadcrumb/
├── hooks/              # Layout Logic
└── index.ts            # Public API Export
```

### **📁 Common Feature** (`/features/common/`)

```
common/
├── ui/                 # Reusable UI Components
│   ├── TableSort/
│   ├── Pagination/
│   ├── ProgressBar/
│   ├── Form/
│   └── Image/
├── hooks/              # Common Hooks
│   └── use-computed-style.ts
├── utils/              # Utility Functions
└── index.ts            # Public API Export
```

## 🚀 **Cách Sử Dụng**

### **Import từ Feature:**

```typescript
// Import từ auth feature
import { LoginForm, useAuth, ProtectedRoute } from "@/features/auth";

// Import từ dashboard feature
import { DashboardHeader } from "@/features/dashboard";

// Import từ layout feature
import { Sidebar, Navbar } from "@/features/layout";

// Import từ common feature
import { TableSort, Pagination } from "@/features/common";
```

### **Thêm Feature Mới:**

1. Tạo thư mục mới trong `/features/`
2. Tổ chức theo cấu trúc chuẩn
3. Tạo `index.ts` để export public API
4. Import và sử dụng trong app

## ✅ **Lợi Ích**

- **Dễ tìm kiếm** - Mỗi feature có thư mục riêng
- **Dễ maintain** - Logic và UI được tách biệt
- **Dễ mở rộng** - Thêm feature mới không ảnh hưởng cũ
- **Reusable** - Components có thể dùng chung
- **Clean imports** - Import paths rõ ràng, ngắn gọn
