import { useEffect, useState } from 'react'
import { apiRequest } from '../lib/queryClient'

interface User {
  id: number
  first_name: string
  last_name: string
  email: string
  username: string
  role: string
}

export function TestComponent() {
  const [users, setUsers] = useState<User[]>([])
  const [error, setError] = useState<string>('')
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const data = await apiRequest<User[]>('GET', 'users')
        setUsers(data)
        setError('')
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Failed to fetch users')
      } finally {
        setLoading(false)
      }
    }

    fetchUsers()
  }, [])

  if (loading) return <div>Loading...</div>
  if (error) return <div>Error: {error}</div>

  return (
    <div className="p-4">
      <h1 className="text-2xl font-bold mb-4">Test Component</h1>
      <div className="space-y-4">
        <div className="bg-green-100 p-4 rounded">
          ✅ React is working
        </div>
        <div className="bg-blue-100 p-4 rounded">
          ✅ Tailwind CSS is working
        </div>
        <div className={`p-4 rounded ${users.length > 0 ? 'bg-green-100' : 'bg-red-100'}`}>
          {users.length > 0 ? '✅' : '❌'} API Connection: {users.length} users found
        </div>
      </div>
      
      {users.length > 0 && (
        <div className="mt-4">
          <h2 className="text-xl font-semibold mb-2">Users:</h2>
          <div className="space-y-2">
            {users.map(user => (
              <div key={user.id} className="bg-white p-3 rounded shadow">
                {user.first_name} {user.last_name} ({user.email})
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  )
} 