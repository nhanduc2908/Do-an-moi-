import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/auth_provider.dart';
import '../../widgets/common/custom_button.dart';

class ProfileScreen extends ConsumerWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final user = ref.watch(authProvider).user;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Profile'),
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            const SizedBox(height: 20),
            CircleAvatar(
              radius: 50,
              backgroundColor: Colors.blue.shade100,
              child: Text(
                user?.initials ?? 'U',
                style: const TextStyle(fontSize: 32, fontWeight: FontWeight.bold),
              ),
            ),
            const SizedBox(height: 16),
            Text(
              user?.name ?? 'User Name',
              style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            Text(
              user?.email ?? 'user@example.com',
              style: const TextStyle(color: Colors.grey),
            ),
            const SizedBox(height: 24),
            Card(
              margin: const EdgeInsets.all(16),
              child: Column(
                children: [
                  _buildMenuItem(Icons.person, 'Edit Profile', () {}),
                  _buildMenuItem(Icons.lock, 'Change Password', () {}),
                  _buildMenuItem(Icons.security, 'Two-Factor Authentication', () {}),
                  _buildMenuItem(Icons.devices, 'Devices', () {}),
                  _buildMenuItem(Icons.history, 'Activity Log', () {}),
                  _buildMenuItem(Icons.notifications, 'Notifications', () {}),
                  _buildMenuItem(Icons.language, 'Language', () {}),
                  _buildMenuItem(Icons.dark_mode, 'Dark Mode', () {}),
                  const Divider(),
                  _buildMenuItem(Icons.logout, 'Logout', () async {
                    await ref.read(authProvider.notifier).logout();
                    Navigator.of(context).pushNamedAndRemoveUntil('/login', (route) => false);
                  }, isDestructive: true),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildMenuItem(IconData icon, String title, VoidCallback onTap, {bool isDestructive = false}) {
    return ListTile(
      leading: Icon(icon, color: isDestructive ? Colors.red : null),
      title: Text(title, style: TextStyle(color: isDestructive ? Colors.red : null)),
      trailing: const Icon(Icons.chevron_right),
      onTap: onTap,
    );
  }
}