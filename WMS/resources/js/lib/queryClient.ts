import { QueryClient, QueryFunction } from "@tanstack/react-query";

const API_BASE_URL = 'http://localhost:5000';

async function throwIfResNotOk(res: Response) {
  if (!res.ok) {
    const text = (await res.text()) || res.statusText;
    throw new Error(`${res.status}: ${text}`);
  }
}

export async function apiRequest(
  method: string,
  url: string,
  data?: unknown | undefined,
): Promise<Response> {
  const fullUrl = url.startsWith('http') ? url : `${API_BASE_URL}${url}`;
  try {
    const res = await fetch(fullUrl, {
      method,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: data ? JSON.stringify(data) : undefined,
      mode: 'cors',
      credentials: 'include',
    });

    if (res.status === 204) {
      return res;
    }

    if (!res.ok) {
      const contentType = res.headers.get('content-type');
      if (contentType && contentType.includes('application/json')) {
        const errorData = await res.json();
        throw new Error(errorData.message || res.statusText);
      } else {
        const text = await res.text();
        throw new Error(text || res.statusText);
      }
    }

    return res;
  } catch (error) {
    console.error('API Request Error:', error);
    throw error;
  }
}

type UnauthorizedBehavior = "returnNull" | "throw";
export const getQueryFn: <T>(options: {
  on401: UnauthorizedBehavior;
}) => QueryFunction<T> =
  ({ on401: unauthorizedBehavior }) =>
  async ({ queryKey }) => {
    const url = queryKey[0] as string;
    const fullUrl = url.startsWith('http') ? url : `${API_BASE_URL}${url}`;
    const res = await fetch(fullUrl, {
      credentials: "include",
      headers: {
        "Accept": "application/json",
      },
    });

    if (unauthorizedBehavior === "returnNull" && res.status === 401) {
      return null;
    }

    if (!res.ok) {
      const contentType = res.headers.get('content-type');
      if (contentType && contentType.includes('application/json')) {
        const errorData = await res.json();
        throw new Error(errorData.message || res.statusText);
      } else {
        const text = await res.text();
        throw new Error(text || res.statusText);
      }
    }

    return res.json();
  };

export const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      queryFn: getQueryFn({ on401: "throw" }),
      refetchInterval: false,
      refetchOnWindowFocus: false,
      staleTime: Infinity,
      retry: false,
    },
    mutations: {
      retry: false,
    },
  },
});
