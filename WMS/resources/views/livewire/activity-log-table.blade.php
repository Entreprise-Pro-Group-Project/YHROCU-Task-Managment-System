<div class="bg-white dark:bg-gray-900 shadow rounded-lg p-6">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Activity Log</h2>
    <div class="space-x-2">
      <a href="#" class="px-3 py-2 text-sm rounded bg-primary text-primary-foreground hover:bg-primary/80">
        Export CSV
      </a>
      <a href="#" class="px-3 py-2 text-sm rounded bg-primary text-primary-foreground hover:bg-primary/80">
        Export PDF
      </a>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
        <tr>
          <th class="px-4 py-2">User</th>
          <th class="px-4 py-2">Action</th>
          <th class="px-4 py-2">Entity</th>
          <th class="px-4 py-2">Before</th>
          <th class="px-4 py-2">After</th>
          <th class="px-4 py-2">Time</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse ($logs as $log)
          @php
            // Ensure $changes is an array (model casts should handle this, but we check to be sure)
            $changes = is_array($log->changes) ? $log->changes : json_decode($log->changes, true);
            $action = $changes['action'] ?? 'updated';
            $before = $changes['before'] ?? [];
            $after  = $changes['after'] ?? [];
          @endphp
          <tr>
            <td class="px-4 py-2">{{ $log->user->name ?? 'Unknown' }}</td>
            <td class="px-4 py-2">{{ ucfirst($action) }} {{ $log->entity_type }}</td>
            <td class="px-4 py-2">{{ ucfirst($log->entity_type) }} #{{ $log->entity_id }}</td>
            <td class="px-4 py-2 text-xs text-gray-600">
              @if(count($before))
                @foreach($before as $key => $val)
                  <div>
                    <span class="font-medium">{{ $key }}:</span> {{ $val }}
                  </div>
                @endforeach
              @else
                <span class="text-gray-400 italic">N/A</span>
              @endif
            </td>
            <td class="px-4 py-2 text-xs text-gray-600">
              @if(count($after))
                @foreach($after as $key => $val)
                  <div>
                    <span class="font-medium">{{ $key }}:</span> {{ $val }}
                  </div>
                @endforeach
              @else
                <span class="text-gray-400 italic">N/A</span>
              @endif
            </td>
            <td class="px-4 py-2 text-xs text-gray-500">
              {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-4 py-4 text-center text-gray-400">No activity yet</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $logs->links() }}
  </div>
</div>
