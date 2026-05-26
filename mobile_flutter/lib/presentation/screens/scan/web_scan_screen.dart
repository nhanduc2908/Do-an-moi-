import 'package:flutter/material.dart';

class WebScanScreen extends StatefulWidget {
  const WebScanScreen({super.key});

  @override
  State<WebScanScreen> createState() => _WebScanScreenState();
}

class _WebScanScreenState extends State<WebScanScreen> {
  final TextEditingController _urlController = TextEditingController();
  bool _isScanning = false;

  Future<void> _startScan() async {
    if (_urlController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter a URL')),
      );
      return;
    }

    setState(() => _isScanning = true);
    await Future.delayed(const Duration(seconds: 3));
    setState(() => _isScanning = false);

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Scan completed')),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Web Scan'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _urlController,
              decoration: const InputDecoration(
                labelText: 'URL',
                hintText: 'https://example.com',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.link),
              ),
              keyboardType: TextInputType.url,
            ),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _isScanning ? null : _startScan,
                child: _isScanning ? const CircularProgressIndicator() : const Text('Start Scan'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}