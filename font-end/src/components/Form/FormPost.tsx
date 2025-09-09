"use client";
import { useForm } from "react-hook-form";
import { useState } from "react";
import { createJobPost, JobFormData } from "../../services/postapi";

export default function NewJobPost() {
  const { register, handleSubmit, reset } = useForm<JobFormData>();
  const [loading, setLoading] = useState(false);

  const onSubmit = async (data: JobFormData) => {
    setLoading(true);
    try {
      await createJobPost(data);
      alert("Đăng tin thành công!");
      reset();
    } catch (err: unknown) {
      if (err instanceof Error) {
        alert(err.message);
      } else {
        alert("Có lỗi xảy ra");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-2xl mx-auto bg-white shadow-lg rounded-2xl p-6">
      <h1 className="text-2xl font-bold mb-4">Đăng tin tuyển dụng</h1>
      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <input
          {...register("job_title")}
          placeholder="Tiêu đề công việc"
          className="w-full border p-2 rounded"
        />
        <input
          {...register("company_name")}
          placeholder="Tên công ty"
          className="w-full border p-2 rounded"
        />
        <textarea
          {...register("description")}
          placeholder="Mô tả công việc"
          className="w-full border p-2 rounded"
        />
        <input
          type="date"
          {...register("application_deadline")}
          className="w-full border p-2 rounded"
        />

        <h2 className="font-semibold">Địa chỉ</h2>
        <input
          {...register("street")}
          placeholder="Đường"
          className="w-full border p-2 rounded"
        />
        <input
          {...register("city")}
          placeholder="Thành phố"
          className="w-full border p-2 rounded"
        />
        <input
          {...register("state")}
          placeholder="Tỉnh/State"
          className="w-full border p-2 rounded"
        />
        <input
          {...register("country")}
          placeholder="Quốc gia"
          className="w-full border p-2 rounded"
        />
        <input
          {...register("postal_code")}
          placeholder="Mã bưu điện"
          className="w-full border p-2 rounded"
        />

        <h2 className="font-semibold">Kinh nghiệm</h2>
        <input
          type="number"
          {...register("years_experience")}
          placeholder="Số năm kinh nghiệm"
          className="w-full border p-2 rounded"
        />

        <h2 className="font-semibold">Ngành nghề</h2>
        <input
          {...register("industry_name")}
          placeholder="Tên ngành nghề"
          className="w-full border p-2 rounded"
        />

        <h2 className="font-semibold">Vị trí tuyển dụng</h2>
        <input
          {...register("position_name")}
          placeholder="Tên vị trí"
          className="w-full border p-2 rounded"
        />
        <input
          type="number"
          {...register("quantity")}
          placeholder="Số lượng"
          className="w-full border p-2 rounded"
        />

        <h2 className="font-semibold">Mức lương</h2>
        <input
          type="number"
          {...register("salary_min")}
          placeholder="Lương tối thiểu"
          className="w-full border p-2 rounded"
        />
        <input
          type="number"
          {...register("salary_max")}
          placeholder="Lương tối đa"
          className="w-full border p-2 rounded"
        />

        <button
          type="submit"
          disabled={loading}
          className="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
        >
          {loading ? "Đang đăng..." : "Đăng tin"}
        </button>
      </form>
    </div>
  );
}
