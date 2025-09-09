import { apiClient, ApiResponse } from "../lib/apiClient";

export interface JobFormData {
  job_title: string;
  company_name: string;
  description: string;
  application_deadline: string;

  street: string;
  city: string;
  state: string;
  country: string;
  postal_code: string;

  years_experience: number;

  industry_name: string;

  position_name: string;
  quantity: number;

  salary_min: number;
  salary_max: number;
}

export async function createJobPost(data: JobFormData): Promise<ApiResponse> {
  return apiClient.request("/api/job-posts", {
    method: "POST",
    body: JSON.stringify(data),
  });
}

export async function getJobs(): Promise<ApiResponse> {
  return apiClient.request("/api/job-posts", {
    method: "GET",
  });
}

export async function getJobById(id: number): Promise<ApiResponse> {
  return apiClient.request(`/api/job-posts/${id}`, {
    method: "GET",
  });
}
