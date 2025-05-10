import { ReactNode } from "react";
import { Button } from "@/components/ui/button";
import { LogOut, UserPlus, Lock } from "lucide-react";

interface LayoutProps {
  children: ReactNode;
  onAddUser: () => void;
}

export function Layout({ children, onAddUser }: LayoutProps) {
  return (
    <div className="min-h-screen flex flex-col">
      <header className="flex items-center justify-between px-6 py-4 bg-white border-b">
        <div className="flex items-center gap-2">
          <h1 className="text-xl font-bold">User Management</h1>
        </div>
        <Button variant="ghost" size="sm" className="gap-2">
          <LogOut className="h-4 w-4" />
          Log Out
        </Button>
      </header>

      <div className="flex flex-1">
        <aside className="w-64 border-r bg-gray-50 p-4">
          <div className="space-y-2">
            <Button onClick={onAddUser} className="w-full gap-2">
              <UserPlus className="h-4 w-4" />
              Add User
            </Button>
            <Button variant="outline" className="w-full gap-2">
              <Lock className="h-4 w-4" />
              Reset Password
            </Button>
          </div>
        </aside>

        <main className="flex-1 p-6">
          {children}
        </main>
      </div>
    </div>
  );
}
