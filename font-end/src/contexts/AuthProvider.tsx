"use client";

import React, { createContext, useContext, useEffect, useState } from "react";

interface AuthContextType {
  token: string | null;
}

const AuthContext = createContext<AuthContextType>({
  token: null,
});

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({
  children,
}) => {
  const [token, setToken] = useState<string | null>(() =>
    localStorage.getItem("token")
  );

  useEffect(() => {
    const handler = (event: MessageEvent) => {
      if (event.origin === "http://localhost:3001" && event.data.token) {
        localStorage.setItem("token", event.data.token);
        setToken(event.data.token);
        console.log("✅ Token received from app 3001:", event.data.token);
      }
    };

    window.addEventListener("message", handler);
    return () => window.removeEventListener("message", handler);
  }, []);

  return (
    <AuthContext.Provider value={{ token }}>{children}</AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
