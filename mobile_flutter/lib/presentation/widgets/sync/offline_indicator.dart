// Đường dẫn: mobile_flutter/lib/presentation/widgets/sync/offline_indicator.dart

import 'package:flutter/material.dart';

class OfflineIndicator extends StatelessWidget {
  final bool isOffline;

  const OfflineIndicator({super.key, required this.isOffline});

  @override
  Widget build(BuildContext context) {
    if (!isOffline) return const SizedBox.shrink();

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(vertical: 8),
      color: Colors.orange.shade100,
      child: const Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.cloud_off, size: 16, color: Colors.orange),
          SizedBox(width: 8),
          Text(
            'You are offline. Data will sync when connection is restored.',
            style: TextStyle(fontSize: 12, color: Colors.orange),
          ),
        ],
      ),
    );
  }
}