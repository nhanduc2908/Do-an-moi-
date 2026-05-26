import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../data/models/user_model.dart';
import '../../../providers/user_provider.dart';
import '../../../providers/role_provider.dart';
import '../../../widgets/common/custom_button.dart';
import '../../../widgets/common/custom_textfield.dart';

class UserManagementScreen extends ConsumerStatefulWidget {
  const UserManagementScreen({super.key});

  @override
  ConsumerState<UserManagementScreen> createState() => _UserManagementScreenState();
}

class _UserManagementScreenState extends ConsumerState<UserManagementScreen> {
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  String _selectedRole = 'viewer';
  String _selectedStatus = 'active';
  UserModel? _editingUser;
  bool _showDialog = false;

  @override
  void initState() {
    super.initState();
    ref.read(userProvider.notifier).loadUsers();
  }

  void _openUserDialog([UserModel? user]) {
    if (user != null) {
      _editingUser = user;
      _nameController.text = user.name ?? '';
      _emailController.text = user.email ?? '';
      _selectedRole = user.role ?? 'viewer';
      _selectedStatus = user.status ?? 'active';
    } else {
      _editingUser = null;
      _nameController.clear();
      _emailController.clear();
      _passwordController.clear();
      _selectedRole = 'viewer';
      _selectedStatus = 'active';
    }
    setState(() => _showDialog = true);
  }

  Future<void> _saveUser() async {
    final data = {
      'name': _nameController.text,
      'email': _emailController.text,
      'role': _selectedRole,
      'status': _selectedStatus,
    };
    
    if (_passwordController.text.isNotEmpty) {
      data['password'] = _passwordController.text;
    }
    
    bool success;
    if (_editingUser != null) {
      success = await ref.read(userProvider.notifier).updateUser(_editingUser!.id!, data);
    } else {
      success = await ref.read(userProvider.notifier).createUser(data);
    }
    
    if (success && mounted) {
      setState(() => _showDialog = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(_editingUser != null ? 'User updated' : 'User created')),
      );
    }
  }

  Future<void> _deleteUser(String id) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Delete User'),
        content: const Text('Are you sure you want to delete this user?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context, false), child: const Text('Cancel')),
          TextButton(onPressed: () => Navigator.pop(context, true), child: const Text('Delete')),
        ],
      ),
    );
    
    if (confirmed == true) {
      await ref.read(userProvider.notifier).deleteUser(id);
    }
  }

  @override
  Widget build(BuildContext context) {
    final userState = ref.watch(userProvider);
    final isAdmin = ref.watch(isAdminProvider);
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('User Management'),
        actions: [
          if (isAdmin)
            IconButton(
              icon: const Icon(Icons.add),
              onPressed: () => _openUserDialog(),
            ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () => ref.read(userProvider.notifier).loadUsers(),
        child: userState.isLoading
            ? const Center(child: CircularProgressIndicator())
            : ListView.builder(
                padding: const EdgeInsets.all(16),
                itemCount: userState.users.length,
                itemBuilder: (context, index) {
                  final user = userState.users[index];
                  return Card(
                    margin: const EdgeInsets.only(bottom: 12),
                    child: ListTile(
                      leading: CircleAvatar(
                        backgroundColor: Colors.blue.shade100,
                        child: Text(user.initials, style: const TextStyle(color: Colors.blue)),
                      ),
                      title: Text(user.name ?? 'Unknown'),
                      subtitle: Text(user.email ?? ''),
                      trailing: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                            decoration: BoxDecoration(
                              color: user.isActive ? Colors.green.withOpacity(0.2) : Colors.grey.withOpacity(0.2),
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: Text(
                              user.status ?? 'inactive',
                              style: TextStyle(color: user.isActive ? Colors.green : Colors.grey),
                            ),
                          ),
                          if (isAdmin) ...[
                            IconButton(
                              icon: const Icon(Icons.edit, size: 20),
                              onPressed: () => _openUserDialog(user),
                            ),
                            IconButton(
                              icon: const Icon(Icons.delete, size: 20, color: Colors.red),
                              onPressed: () => _deleteUser(user.id!),
                            ),
                          ],
                        ],
                      ),
                    ),
                  );
                },
              ),
      ),
      floatingActionButton: isAdmin
          ? FloatingActionButton(
              onPressed: () => _openUserDialog(),
              child: const Icon(Icons.add),
            )
          : null,
    );
  }
}