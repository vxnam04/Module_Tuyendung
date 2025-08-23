"use client";

import { useState } from "react";
import styles from "./login.module.css";
import Link from "next/link";
import { FaGoogle, FaGithub, FaFacebook } from "react-icons/fa";

export default function LoginPage() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const handleLogin = () => {
    console.log("Email:", email);
    console.log("Password:", password);
  };

  return (
    <div className={styles.box}>
      <div className={styles.container}>
        <h1 className={styles.logo}>Your Logo</h1>
        <span className={styles.taglogin}>Login</span>

        <div className={styles.formGroup}>
          <span className={styles.tagemail}>Email</span>
          <input
            className={styles.inputField}
            placeholder="Email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
        </div>

        <div className={styles.formGroup}>
          <span className={styles.tagpassword}>Password</span>
          <input
            className={styles.inputField}
            type="password"
            placeholder="Mật khẩu"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />
        </div>

        <span className={styles.tagforgot}>Forgot password?</span>
        <Link href="/authorized/teacher">
          <button className={styles.loginButton} onClick={handleLogin}>
            Login
          </button>
        </Link>

        <div className={styles.tagor}>Or continue with</div>

        <div className={styles.socialLogin}>
          <button className={styles.socialButton}>
            <FaGoogle className={styles.googleIcon} size={20} />
          </button>
          <button className={styles.socialButton}>
            <FaGithub className={styles.githubIcon} size={20} />
          </button>
          <button className={styles.socialButton}>
            <FaFacebook className={styles.facebookIcon} size={20} />
          </button>
        </div>

        <div className={styles.register}>
          Don’t have an account yet?{" "}
          <Link href="/auth/register" className={styles.registerLink}>
            Register for free
          </Link>
        </div>
      </div>
    </div>
  );
}
