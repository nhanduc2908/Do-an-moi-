// Đường dẫn: mobile_flutter/lib/presentation/widgets/security/compliance_status_indicator.dart

import 'package:flutter/material.dart';

class ComplianceStatusIndicator extends StatelessWidget {
  final String status;
  final double percentage;

  const ComplianceStatusIndicator({
    super.key,
    required this.status,
    required this.percentage,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: _getStatusColor().withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: _getStatusColor().withOpacity(0.3)),
      ),
      child: Row(
        children: [
          Icon(_getStatusIcon(), color: _getStatusColor()),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  status,
                  style: TextStyle(color: _getStatusColor(), fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 4),
                LinearProgressIndicator(
                  value: percentage / 100,
                  backgroundColor: Colors.grey.shade200,
                  color: _getStatusColor(),
                  height: 4,
                ),
              ],
            ),
          ),
          Text(
            '${percentage.toInt()}%',
            style: TextStyle(color: _getStatusColor(), fontWeight: FontWeight.bold),
          ),
        ],
      ),
    );
  }

  Color _getStatusColor() {
    switch (status.toLowerCase()) {
      case 'compliant': return Colors.green;
      case 'partial': return Colors.orange;
      case 'non-compliant': return Colors.red;
      default: return Colors.blue;
    }
  }

  IconData _getStatusIcon() {
    switch (status.toLowerCase()) {
      case 'compliant': return Icons.check_circle;
      case 'partial': return Icons.warning;
      case 'non-compliant': return Icons.error;
      default: return Icons.help;
    }
  }
}