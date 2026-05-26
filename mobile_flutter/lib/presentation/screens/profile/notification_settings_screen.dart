// Đường dẫn: mobile_flutter/lib/presentation/screens/profile/notification_settings_screen.dart

import 'package:flutter/material.dart';

class NotificationSettingsScreen extends StatefulWidget {
  const NotificationSettingsScreen({super.key});

  @override
  State<NotificationSettingsScreen> createState() => _NotificationSettingsScreenState();
}

class _NotificationSettingsScreenState extends State<NotificationSettingsScreen> {
  bool _pushEnabled = true;
  bool _emailEnabled = true;
  bool _securityAlerts = true;
  bool _reportUpdates = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Notification Settings'),
      ),
      body: ListView(
        children: [
          SwitchListTile(
            title: const Text('Push Notifications'),
            subtitle: const Text('Receive push notifications'),
            value: _pushEnabled,
            onChanged: (value) => setState(() => _pushEnabled = value),
          ),
          SwitchListTile(
            title: const Text('Email Notifications'),
            subtitle: const Text('Receive email notifications'),
            value: _emailEnabled,
            onChanged: (value) => setState(() => _emailEnabled = value),
          ),
          const Divider(),
          SwitchListTile(
            title: const Text('Security Alerts'),
            subtitle: const Text('Critical security alerts'),
            value: _securityAlerts,
            onChanged: (value) => setState(() => _securityAlerts = value),
          ),
          SwitchListTile(
            title: const Text('Report Updates'),
            subtitle: const Text('When reports are generated'),
            value: _reportUpdates,
            onChanged: (value) => setState(() => _reportUpdates = value),
          ),
        ],
      ),
    );
  }
}