// Đường dẫn: mobile_flutter/lib/presentation/widgets/dashboard/threat_map.dart

import 'package:flutter/material.dart';

class ThreatMap extends StatelessWidget {
  final List<ThreatLocation> threats;

  const ThreatMap({super.key, required this.threats});

  @override
  Widget build(BuildContext context) {
    return Container(
      height: 200,
      decoration: BoxDecoration(
        color: Colors.grey.shade100,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.grey.shade300),
      ),
      child: Stack(
        children: threats.map((threat) {
          return Positioned(
            left: threat.x,
            top: threat.y,
            child: Tooltip(
              message: '${threat.type}: ${threat.severity}',
              child: Container(
                width: 12,
                height: 12,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: _getSeverityColor(threat.severity),
                  boxShadow: [
                    BoxShadow(
                      color: _getSeverityColor(threat.severity).withOpacity(0.5),
                      blurRadius: 4,
                      spreadRadius: 2,
                    ),
                  ],
                ),
              ),
            ),
          );
        }).toList(),
      ),
    );
  }

  Color _getSeverityColor(String severity) {
    switch (severity.toLowerCase()) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      case 'medium': return Colors.yellow;
      default: return Colors.green;
    }
  }
}

class ThreatLocation {
  final double x;
  final double y;
  final String type;
  final String severity;

  ThreatLocation({
    required this.x,
    required this.y,
    required this.type,
    required this.severity,
  });
}