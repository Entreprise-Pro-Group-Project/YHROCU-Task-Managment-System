import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import { useMutation } from "@tanstack/react-query";
import { apiRequest } from "@/lib/queryClient";
import { useToast } from "@/hooks/use-toast";
import { queryClient } from "@/lib/queryClient";
import type { User } from "@shared/schema";

interface DeleteUserDialogProps {
  user: User | null;
  open: boolean;
  onClose: () => void;
}

export function DeleteUserDialog({ user, open, onClose }: DeleteUserDialogProps) {
  const { toast } = useToast();

  const deleteUser = useMutation({
    mutationFn: async (userId: number) => {
      console.log('Attempting to delete user:', userId);
      const response = await apiRequest("DELETE", `/api/users/${userId}`);
      console.log('Delete response:', response.status);
      
      if (response.status === 204) {
        return true;
      }
      throw new Error("Failed to delete user");
    },
    onSuccess: () => {
      console.log('Delete successful, invalidating queries');
      queryClient.invalidateQueries({ queryKey: ["/api/users"] });
      toast({
        title: "Success",
        description: "User has been deleted successfully",
      });
      onClose();
    },
    onError: (error: Error) => {
      console.error('Delete error:', error);
      toast({
        title: "Error",
        description: error.message || "Failed to delete user",
        variant: "destructive",
      });
    },
  });

  // Handle dialog close
  const handleOpenChange = (open: boolean) => {
    if (!open) {
      onClose();
    }
  };

  const handleDelete = () => {
    if (!user) {
      toast({
        title: "Error",
        description: "No user selected for deletion",
        variant: "destructive",
      });
      return;
    }
    deleteUser.mutate(user.id);
  };

  // If no user is selected or dialog is not open, don't render
  if (!user || !open) return null;

  return (
    <AlertDialog open={open} onOpenChange={handleOpenChange}>
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Are you sure?</AlertDialogTitle>
          <AlertDialogDescription>
            This action cannot be undone. This will permanently delete the user
            account for {user.firstName} {user.lastName}.
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel disabled={deleteUser.isPending}>Cancel</AlertDialogCancel>
          <AlertDialogAction
            onClick={handleDelete}
            disabled={deleteUser.isPending}
            className="bg-destructive text-destructive-foreground hover:bg-destructive/90"
          >
            {deleteUser.isPending ? "Deleting..." : "Delete"}
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  );
}
