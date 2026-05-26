import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/setting_provider.dart';
import '../../../providers/theme_provider.dart';
import '../../../providers/language_provider.dart';
import '../../../widgets/common/custom_button.dart';

class SystemSettingsScreen extends ConsumerStatefulWidget {
  const SystemSettingsScreen({super.key});

  @override
  ConsumerState<SystemSettingsScreen> createState() => _SystemSettingsScreenState();
}

class _SystemSettingsScreenState extends ConsumerState<SystemSettingsScreen> {
  bool _notifications = true;
  bool _autoSync = true;
  bool _darkMode = false;
  String _language = 'en';
  String _syncInterval = '15';

  @override
  Widget build(BuildContext context) {
    final currentTheme = ref.watch(themeProvider);
    final currentLocale = ref.watch(languageProvider);
    
    return Scaffold(
      appBar: AppBar(title: const Text('System Settings')),
      body: ListView(
        children: [
          const SizedBox(height: 16),
          _buildSection(
            title: 'Appearance',
            children: [
              SwitchListTile(
                title: const Text('Dark Mode'),
                subtitle: const Text('Enable dark theme'),
                value: currentTheme == ThemeMode.dark,
                onChanged: (value) {
                  ref.read(themeProvider.notifier).setTheme(
                    value ? ThemeMode.dark : ThemeMode.light,
                  );
                },
              ),
              ListTile(
                title: const Text('Language'),
                subtitle: Text(_getLanguageName(_language)),
                trailing: DropdownButton<String>(
                  value: _language,
                  items: const [
                    DropdownMenuItem(value: 'en', child: Text('English')),
                    DropdownMenuItem(value: 'vi', child: Text('Tiếng Việt')),
                    DropdownMenuItem(value: 'ja', child: Text('日本語')),
                    DropdownMenuItem(value: 'ko', child: Text('한국어')),
                  ],
                  onChanged: (value) {
                    if (value != null) {
                      setState(() => _language = value);
                      ref.read(languageProvider.notifier).setLanguage(Locale(value));
                    }
                  },
                ),
              ),
            ],
          ),
          _buildSection(
            title: 'Notifications',
            children: [
              SwitchListTile(
                title: const Text('Push Notifications'),
                subtitle: const Text('Receive push notifications'),
                value: _notifications,
                onChanged: (value) => setState(() => _notifications = value),
              ),
              SwitchListTile(
                title: const Text('Email Alerts'),
                subtitle: const Text('Receive email alerts'),
                value: true,
                onChanged: (value) {},
              ),
            ],
          ),
          _buildSection(
            title: 'Data & Sync',
            children: [
              SwitchListTile(
                title: const Text('Auto Sync'),
                subtitle: const Text('Automatically sync data in background'),
                value: _autoSync,
                onChanged: (value) => setState(() => _autoSync = value),
              ),
              ListTile(
                title: const Text('Sync Interval'),
                subtitle: Text('Every $_syncInterval minutes'),
                trailing: DropdownButton<String>(
                  value: _syncInterval,
                  items: const [
                    DropdownMenuItem(value: '5', child: Text('5 minutes')),
                    DropdownMenuItem(value: '15', child: Text('15 minutes')),
                    DropdownMenuItem(value: '30', child: Text('30 minutes')),
                    DropdownMenuItem(value: '60', child: Text('1 hour')),
                  ],
                  onChanged: (value) => setState(() => _syncInterval = value!),
                ),
              ),
              ListTile(
                title: const Text('Offline Mode'),
                subtitle: const Text('Enable offline support'),
                trailing: const Icon(Icons.cloud_off),
                onTap: () {},
              ),
            ],
          ),
          _buildSection(
            title: 'Security',
            children: [
              ListTile(
                title: const Text('Change Password'),
                subtitle: const Text('Update your password'),
                trailing: const Icon(Icons.chevron_right),
                onTap: () {},
              ),
              ListTile(
                title: const Text('Two-Factor Authentication'),
                subtitle: const Text('Secure your account with 2FA'),
                trailing: Switch(value: false, onChanged: (value) {}),
              ),
              ListTile(
                title: const Text('Biometric Login'),
                subtitle: const Text('Use fingerprint or face recognition'),
                trailing: Switch(value: false, onChanged: (value) {}),
              ),
            ],
          ),
          _buildSection(
            title: 'About',
            children: [
              ListTile(
                title: const Text('Version'),
                subtitle: const Text('1.0.0'),
                trailing: const Icon(Icons.info_outline),
              ),
              ListTile(
                title: const Text('Terms of Service'),
                trailing: const Icon(Icons.chevron_right),
                onTap: () {},
              ),
              ListTile(
                title: const Text('Privacy Policy'),
                trailing: const Icon(Icons.chevron_right),
                onTap: () {},
              ),
            ],
          ),
          const SizedBox(height: 24),
          Padding(
            padding: const EdgeInsets.all(16),
            child: CustomButton(
              text: 'Save Settings',
              onPressed: () {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Settings saved')),
                );
              },
            ),
          ),
          const SizedBox(height: 32),
        ],
      ),
    );
  }

  Widget _buildSection({required String title, required List<Widget> children}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
          child: Text(
            title,
            style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.grey),
          ),
        ),
        Card(
          margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
          child: Column(children: children),
        ),
        const SizedBox(height: 16),
      ],
    );
  }

  String _getLanguageName(String code) {
    switch (code) {
      case 'en': return 'English';
      case 'vi': return 'Tiếng Việt';
      case 'ja': return '日本語';
      case 'ko': return '한국어';
      default: return 'English';
    }
  }
}