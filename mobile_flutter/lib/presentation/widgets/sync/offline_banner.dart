import 'package:flutter/material.dart';

class OfflineBanner extends StatelessWidget {
  final VoidCallback onRetry;

  const OfflineBanner({super.key, required this.onRetry});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(12),
      color: Colors.orange.shade100,
      child: Row(
        children: [
          const Icon(Icons.cloud_off, color: Colors.orange),
          const SizedBox(width: 12),
          const Expanded(
            child: Text(
              'You are offline. Some features may be limited.',
              style: TextStyle(color: Colors.orange),
            ),
          ),
          TextButton(
            onPressed: onRetry,
            child: const Text('Retry'),
          ),
        ],
      ),
    );
  }
}