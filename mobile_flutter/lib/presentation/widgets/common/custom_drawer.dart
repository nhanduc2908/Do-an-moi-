import 'package:flutter/material.dart';

class CustomDrawer extends StatelessWidget {
  final String userName;
  final String userEmail;
  final VoidCallback onProfileTap;
  final VoidCallback onSettingsTap;
  final VoidCallback onLogoutTap;

  const CustomDrawer({
    super.key,
    required this.userName,
    required this.userEmail,
    required this.onProfileTap,
    required this.onSettingsTap,
    required this.onLogoutTap,
  });

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: Column(
        children: [
          UserAccountsDrawerHeader(
            accountName: Text(userName),
            accountEmail: Text(userEmail),
            currentAccountPicture: CircleAvatar(
              backgroundColor: Colors.white,
              child: Text(userName.isNotEmpty ? userName[0].toUpperCase() : 'U'),
            ),
          ),
          ListTile(
            leading: const Icon(Icons.person),
            title: const Text('Profile'),
            onTap: onProfileTap,
          ),
          ListTile(
            leading: const Icon(Icons.settings),
            title: const Text('Settings'),
            onTap: onSettingsTap,
          ),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.logout, color: Colors.red),
            title: const Text('Logout', style: TextStyle(color: Colors.red)),
            onTap: onLogoutTap,
          ),
        ],
      ),
    );
  }
}