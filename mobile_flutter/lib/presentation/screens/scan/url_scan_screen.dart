// Đường dẫn: mobile_flutter/lib/presentation/screens/scan/url_scan_screen.dart

import 'package:flutter/material.dart';

class UrlScanScreen extends StatefulWidget {
  const UrlScanScreen({super.key});

  @override
  State<UrlScanScreen> createState() => _UrlScanScreenState();
}

class _UrlScanScreenState extends State<UrlScanScreen> {
  final TextEditingController _urlController = TextEditingController();
  bool _isScanning = false;
  Map<String, dynamic>? _result;

  Future<void> _scanUrl() async {
    if (_urlController.text.isEmpty) return;

    setState(() {
      _isScanning = true;
      _result = null;
    });

    await Future.delayed(const Duration(seconds: 2));

    setState(() {
      _isScanning = false;
      _result = {
        'url': _urlController.text,
        'safe': true,
        'category': 'Safe',
        'reputation': 'Good',
      };
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('URL Scan'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _urlController,
              decoration: const InputDecoration(
                labelText: 'Enter URL',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.link),
              ),
              keyboardType: TextInputType.url,
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: _isScanning ? null : _scanUrl,
              child: _isScanning ? const CircularProgressIndicator() : const Text('Scan URL'),
            ),
            if (_result != null) ...[
              const SizedBox(height: 24),
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: [
                      Icon(
                        _result!['safe'] ? Icons.check_circle : Icons.warning,
                        size: 48,
                        color: _result!['safe'] ? Colors.green : Colors.red,
                      ),
                      const SizedBox(height: 8),
                      Text('URL: ${_result!['url']}'),
                      Text('Status: ${_result!['category']}'),
                      Text('Reputation: ${_result!['reputation']}'),
                    ],
                  ),
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }
}