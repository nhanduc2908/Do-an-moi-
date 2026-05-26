import 'package:flutter/material.dart';
import 'package:permission_handler/permission_handler.dart';

class PermissionRequestScreen extends StatefulWidget {
  const PermissionRequestScreen({super.key});

  @override
  State<PermissionRequestScreen> createState() => _PermissionRequestScreenState();
}

class _PermissionRequestScreenState extends State<PermissionRequestScreen> {
  final List<Map<String, dynamic>> _permissions = [
    {'name': 'Notification', 'icon': Icons.notifications, 'permission': Permission.notification, 'granted': false},
    {'name': 'Camera', 'icon': Icons.camera_alt, 'permission': Permission.camera, 'granted': false},
    {'name': 'Storage', 'icon': Icons.storage, 'permission': Permission.storage, 'granted': false},
  ];

  Future<void> _requestPermission(Map<String, dynamic> permission) async {
    final status = await permission['permission'].request();
    setState(() {
      permission['granted'] = status.isGranted;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Permissions'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            const Text(
              'Allow Permissions',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            const Text(
              'Please grant the following permissions for the app to function properly',
              textAlign: TextAlign.center,
              style: TextStyle(color: Colors.grey),
            ),
            const SizedBox(height: 32),
            ..._permissions.map((permission) => Card(
              margin: const EdgeInsets.only(bottom: 12),
              child: ListTile(
                leading: Icon(permission['icon'], size: 32),
                title: Text(permission['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
                trailing: permission['granted']
                    ? const Icon(Icons.check_circle, color: Colors.green)
                    : ElevatedButton(
                        onPressed: () => _requestPermission(permission),
                        child: const Text('Allow'),
                      ),
              ),
            )),
            const Spacer(),
            ElevatedButton(
              onPressed: () {},
              style: ElevatedButton.styleFrom(
                minimumSize: const Size(double.infinity, 48),
              ),
              child: const Text('Continue'),
            ),
          ],
        ),
      ),
    );
  }
}