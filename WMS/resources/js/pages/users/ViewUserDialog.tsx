import { Dialog, DialogContent, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import type { User } from "@shared/schema";

interface ViewUserDialogProps {
  user: User | null;
  open: boolean;
  onClose: () => void;
}

export function ViewUserDialog({ user, open, onClose }: ViewUserDialogProps) {
  if (!user) return null;

  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>View User Details</DialogTitle>
        </DialogHeader>

        <div className="space-y-4">
          <div>
            <h4 className="font-medium">Name</h4>
            <p className="text-sm text-muted-foreground">
              {user.firstName} {user.lastName}
            </p>
          </div>

          <div>
            <h4 className="font-medium">Username</h4>
            <p className="text-sm text-muted-foreground">{user.username}</p>
          </div>

          <div>
            <h4 className="font-medium">Email Address</h4>
            <p className="text-sm text-muted-foreground">{user.email}</p>
          </div>

          <div>
            <h4 className="font-medium">Phone Number</h4>
            <p className="text-sm text-muted-foreground">{user.phoneNumber}</p>
          </div>

          <div>
            <h4 className="font-medium">Role</h4>
            <p className="text-sm text-muted-foreground capitalize">{user.role}</p>
          </div>

          <div className="flex justify-end">
            <Button type="button" onClick={onClose}>
              Close
            </Button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
}