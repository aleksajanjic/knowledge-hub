<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="color: #FAFAFA; font-size: 20px; font-weight: 600;">{{ __('User Management') }}</h3>
        <button onclick="openCreateUserModal()"
            style="padding: 10px 20px; background: #10B981; color: #18181B; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px;">
            + {{ __('Add User') }}
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #3F3F46;">
                    <th
                        style="padding: 12px; text-align: left; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                        {{ __('ID') }}</th>
                    <th
                        style="padding: 12px; text-align: left; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                        {{ __('Name') }}</th>
                    <th
                        style="padding: 12px; text-align: left; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                        {{ __('Email') }}</th>
                    <th
                        style="padding: 12px; text-align: left; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                        {{ __('Role') }}</th>
                    <th
                        style="padding: 12px; text-align: left; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                        {{ __('Joined') }}</th>
                    <th
                        style="padding: 12px; text-align: right; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                        {{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #3F3F46;">
                        <td style="padding: 12px; color: #D4D4D8; font-size: 14px;">{{ $user->id }}</td>
                        <td style="padding: 12px; color: #FAFAFA; font-size: 14px; font-weight: 500;">
                            {{ $user->name }}</td>
                        <td style="padding: 12px; color: #D4D4D8; font-size: 14px;">{{ $user->email }}</td>
                        <td style="padding: 12px;">
                            <span
                                style="padding: 4px 10px; background: {{ $user->role === 'admin' ? '#10B981' : ($user->role === 'moderator' ? '#3B82F6' : '#71717A') }}; color: white; font-size: 12px; font-weight: 600; border-radius: 6px; text-transform: capitalize;">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td style="padding: 12px; color: #A1A1AA; font-size: 14px;">
                            {{ $user->created_at->format('M d, Y') }}</td>
                        <td style="padding: 12px; text-align: right;">
                            <button onclick="editUser({{ $user->id }})"
                                style="padding: 6px 12px; background: transparent; border: 1px solid #3F3F46; color: #D4D4D8; border-radius: 6px; cursor: pointer; font-size: 13px; margin-right: 8px;"
                                onmouseover="this.style.background='#3F3F46'"
                                onmouseout="this.style.background='transparent'">
                                {{ __('Edit') }}
                            </button>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                style="display: inline;"
                                onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="padding: 6px 12px; background: transparent; border: 1px solid #F43F5E; color: #F43F5E; border-radius: 6px; cursor: pointer; font-size: 13px;"
                                    onmouseover="this.style.background='#F43F5E'; this.style.color='white'"
                                    onmouseout="this.style.background='transparent'; this.style.color='#F43F5E'">
                                    {{ __('Delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #71717A;">
                            {{ __('No users found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>
