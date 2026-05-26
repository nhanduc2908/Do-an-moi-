// Đường dẫn: mobile_flutter/lib/presentation/widgets/security/security_scan_card.dart

import 'package:flutter/material.dart';

class SecurityScanCard extends StatelessWidget {
  final String title;
  final String status;
  final int findings;
  final DateTime lastScan;
  final VoidCallback onScan;

  const SecurityScanCard({
    super.key,
    required this.title,
    required this.status,
    required this.findings,
    required this.lastScan,
    required this.onScan,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(title, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: _getStatusColor(status).withOpacity(0.2),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Text(
                    status,
                    style: TextStyle(color: _getStatusColor(status), fontSize: 12),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                const Icon(Icons.bug_report, size: 20, color: Colors.grey),
                const SizedBox(width: 8),
                Text('$findings vulnerabilities found'),
              ],
            ),
            const SizedBox(height: 8),
            Row(
              children: [
                const Icon(Icons.schedule, size: 20, color: Colors.grey),
                const SizedBox(width: 8),
                Text('Last scan: ${_formatDate(lastScan)}'),
              ],
            ),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                onPressed: onScan,
                icon: const Icon(Icons.play_arrow),
                label: const Text('Run Scan'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status.toLowerCase()) {
      case 'good': return Colors.green;
      case 'warning': return Colors.orange;
      case 'critical': return Colors.red;
      default: return Colors.blue;
    }
  }

  String _formatDate(DateTime date) {
    return '${date.day}/${date.month}/${date.year}';
  }
}