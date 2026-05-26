// Đường dẫn: mobile_flutter/lib/presentation/widgets/report/report_preview.dart

import 'package:flutter/material.dart';

class ReportPreview extends StatelessWidget {
  final String reportUrl;
  final String reportName;

  const ReportPreview({
    super.key,
    required this.reportUrl,
    required this.reportName,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            height: 200,
            decoration: BoxDecoration(
              color: Colors.grey.shade200,
              borderRadius: const BorderRadius.vertical(top: Radius.circular(12)),
            ),
            child: const Center(
              child: Icon(Icons.picture_as_pdf, size: 48, color: Colors.red),
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(12),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  reportName,
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 8),
                Row(
                  children: [
                    const Icon(Icons.picture_as_pdf, size: 16, color: Colors.red),
                    const SizedBox(width: 4),
                    const Text('PDF', style: TextStyle(fontSize: 12)),
                    const Spacer(),
                    TextButton(
                      onPressed: () {},
                      child: const Text('Preview'),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}